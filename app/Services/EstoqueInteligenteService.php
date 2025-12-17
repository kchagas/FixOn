<?php

namespace App\Services;

use App\Models\PecaModel;
use App\Models\MovimentacaoModel;
use App\Models\ConfigEstoqueModel;

/**
 * ==========================================================
 * EstoqueInteligenteService
 * ----------------------------------------------------------
 * Responsável por:
 * - Calcular consumo médio (com base em saídas)
 * - Prever dias para zerar
 * - Classificar risco (urgente/crítico/atenção/ok)
 * - Sugerir quantidade de reposição (cobertura de X dias)
 * ==========================================================
 */
class EstoqueInteligenteService
{
    protected PecaModel $pecaModel;
    protected MovimentacaoModel $movModel;
    protected ConfigEstoqueModel $configModel;

    public function __construct()
    {
        $this->pecaModel   = new PecaModel();
        $this->movModel    = new MovimentacaoModel();
        $this->configModel = new ConfigEstoqueModel();
    }

    /**
     * Retorna config da empresa (cria se não existir).
     */
    public function getConfig(int $empresaId): array
    {
        return $this->configModel->getOrCreateByEmpresa($empresaId);
    }

    /**
     * ----------------------------------------------------------
     * Busca peças da empresa + consumo agregado (últimos N dias)
     * ----------------------------------------------------------
     * Estratégia:
     * - Buscar todas as peças da empresa
     * - Calcular total de saídas por peça na janela
     * - Consumo médio/dia = total_saidas / N
     */
    public function gerarSnapshot(int $empresaId): array
    {
        // 1) Config (parâmetros do motor inteligente)
        $config = $this->getConfig($empresaId);

        $janelaDias   = (int)($config['janela_consumo_dias'] ?? 30);
        $coberturaDias = (int)($config['cobertura_reposicao_dias'] ?? 15);

        // Segurança mínima: evita divisão por zero
        if ($janelaDias <= 0) {
            $janelaDias = 30;
        }

        // 2) Busca todas as peças da empresa
        // OBS: aqui você pode fazer join com categorias se quiser exibir na tela
        $pecas = $this->pecaModel
            ->where('empresa_id', $empresaId)
            ->orderBy('nome', 'ASC')
            ->findAll();

        // 3) Total de saídas por peça na janela (agregado em 1 query)
        // - Saída = movimentacoes_estoque.tipo = 'saida'
        // - Janela = created_at >= NOW() - N dias
        $dataLimite = date('Y-m-d H:i:s', strtotime("-{$janelaDias} days"));

        $saidasAgg = $this->movModel
            ->select('peca_id, SUM(quantidade) AS total_saidas')
            ->where('tipo', 'saida')
            ->where('created_at >=', $dataLimite)
            ->groupBy('peca_id')
            ->findAll();

        // Converte para mapa [peca_id => total_saidas]
        $mapSaidas = [];
        foreach ($saidasAgg as $row) {
            $mapSaidas[(int)$row['peca_id']] = (float)$row['total_saidas'];
        }

        // 4) Calcula inteligência peça a peça
        $itens = [];

        foreach ($pecas as $p) {

            $pecaId        = (int)$p['id'];
            $estoqueAtual  = (int)$p['estoque_atual'];
            $estoqueMinimo = (int)$p['estoque_minimo'];

            // Total saídas na janela (se não existir, vira 0)
            $totalSaidas = $mapSaidas[$pecaId] ?? 0;

            // Consumo médio/dia
            $consumoMedioDia = $totalSaidas > 0
                ? round($totalSaidas / $janelaDias, 2)
                : 0.00;

            // Previsão de dias para zerar
            // - Se consumo médio for 0, não dá para prever (null)
            $diasParaZerar = null;
            if ($consumoMedioDia > 0) {
                $diasParaZerar = round($estoqueAtual / $consumoMedioDia, 2);
            }

            // Classificação do risco (combina regra do mínimo + previsão)
            $nivel = $this->classificarNivel(
                $estoqueAtual,
                $estoqueMinimo,
                $diasParaZerar,
                $config
            );

            // Sugestão de compra:
            // - Se consumo médio for 0, sugestão = 0 (não inventa número)
            // - Caso contrário, cobre X dias - estoque atual
            $qtdSugerida = 0;
            if ($consumoMedioDia > 0) {
                $meta = (int)ceil($consumoMedioDia * $coberturaDias);
                $qtdSugerida = max(0, $meta - $estoqueAtual);
            }

            // Mensagem curta para o painel
            $mensagem = $this->gerarMensagem($nivel, $diasParaZerar);

            // Monta o item final (snapshot)
            $itens[] = [
                'peca_id'          => $pecaId,
                'nome'             => $p['nome'],
                'sku'              => $p['sku'] ?? '',
                'estoque_atual'    => $estoqueAtual,
                'estoque_minimo'   => $estoqueMinimo,
                'consumo_medio_dia'=> $consumoMedioDia,
                'dias_para_zerar'  => $diasParaZerar,
                'nivel'            => $nivel,
                'qtd_sugerida'     => $qtdSugerida,
                'mensagem'         => $mensagem,
            ];
        }

        return [
            'config' => $config,
            'itens'  => $itens,
        ];
    }

    /**
     * Define o nível (urgente/crítico/atenção/ok).
     * Regras (ERP real):
     * - Se estoque = 0 -> urgente
     * - Se estoque <= mínimo -> crítico
     * - Se dias para zerar <= urgente -> urgente
     * - Se dias para zerar <= crítico -> crítico
     * - Se estoque <= mínimo+2 -> atenção (seu padrão anterior)
     * - Caso contrário -> ok
     */
    protected function classificarNivel(
        int $estoqueAtual,
        int $estoqueMinimo,
        ?float $diasParaZerar,
        array $config
    ): string {
        $diasUrgente = (int)($config['dias_alerta_urgente'] ?? 2);
        $diasCritico = (int)($config['dias_alerta_critico'] ?? 5);

        // 1) Zerado é sempre urgente
        if ($estoqueAtual === 0) {
            return 'urgente';
        }

        // 2) Regra clássica do ERP (mínimo)
        if ($estoqueAtual <= $estoqueMinimo) {
            return 'critico';
        }

        // 3) Regra por previsão (se houver consumo)
        if ($diasParaZerar !== null) {
            if ($diasParaZerar <= $diasUrgente) {
                return 'urgente';
            }
            if ($diasParaZerar <= $diasCritico) {
                return 'critico';
            }
        }

        // 4) Sua faixa “atenção”
        if ($estoqueAtual <= ($estoqueMinimo + 2)) {
            return 'atencao';
        }

        return 'ok';
    }

    /**
     * Mensagem curta para o painel de alertas.
     */
    protected function gerarMensagem(string $nivel, ?float $diasParaZerar): string
    {
        switch ($nivel) {
            case 'urgente':
                return $diasParaZerar !== null
                    ? "Risco alto: pode zerar em ~{$diasParaZerar} dia(s)."
                    : "Urgente: estoque zerado ou risco imediato.";
            case 'critico':
                return $diasParaZerar !== null
                    ? "Crítico: previsão de falta em ~{$diasParaZerar} dia(s)."
                    : "Crítico: abaixo/igual ao mínimo.";
            case 'atencao':
                return "Atenção: próximo do mínimo (verifique tendência).";
            default:
                return "OK: estoque dentro do esperado.";
        }
    }
}
