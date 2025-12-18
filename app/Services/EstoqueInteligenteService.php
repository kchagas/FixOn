<?php

namespace App\Services;

use App\Models\PecaModel;


/**
 * ==========================================================
 * MOTOR DE ESTOQUE INTELIGENTE (PASSO 5)
 * ----------------------------------------------------------
 * Responsável por:
 * - Calcular consumo médio
 * - Prever dias para zerar
 * - Definir nível (ok / atenção / crítico / urgente)
 * - Gerar sugestão de compra
 * - Gerar mensagem humana
 * ==========================================================
 */
class EstoqueInteligenteService
{
    /**
     * Gera um snapshot completo do estoque
     */
    public function gerarSnapshot(int $empresaId): array
    {
        $pecaModel = new PecaModel();

        // Configurações padrão (depois pode virar tabela)
        $config = [
            'janela_consumo_dias'      => 30,
            'cobertura_reposicao_dias' => 15,
        ];

        $pecas = $pecaModel
            ->where('empresa_id', $empresaId)
            ->findAll();

        $itens = [];

        foreach ($pecas as $p) {

            // Consumo médio diário (simples por enquanto)
            $consumoDia = $this->calcularConsumoMedio($p);

            // Dias para zerar
            $diasParaZerar = ($consumoDia > 0)
                ? $p['estoque_atual'] / $consumoDia
                : null;

            // Classificação do nível
            $nivel = $this->definirNivel(
                (int)$p['estoque_atual'],
                (int)$p['estoque_minimo'],
                $diasParaZerar
            );

            // Quantidade sugerida
            $qtdSugerida = $this->calcularReposicao(
                (int)$p['estoque_atual'],
                $consumoDia,
                $config['cobertura_reposicao_dias']
            );

            // Mensagem humana
            $mensagem = $this->gerarMensagem($nivel, $diasParaZerar);

            $itens[] = [
                'peca_id'           => $p['id'],
                'nome'              => $p['nome'],
                'sku'               => $p['sku'],
                'estoque_atual'     => (int)$p['estoque_atual'],
                'estoque_minimo'    => (int)$p['estoque_minimo'],
                'consumo_medio_dia' => round($consumoDia, 2),
                'dias_para_zerar'   => $diasParaZerar,
                'nivel'             => $nivel,
                'qtd_sugerida'      => $qtdSugerida,
                'mensagem'          => $mensagem,
            ];
        }

        return [
            'config' => $config,
            'itens'  => $itens,
        ];
    }

    /**
     * Consumo médio diário (MVP)
     */
    protected function calcularConsumoMedio(array $p): float
    {
        // MVP: consumo fictício simples
        return max(0.01, ($p['estoque_minimo'] / 30));
    }

    /**
     * Define o nível do alerta
     */
    protected function definirNivel(int $estoque, int $minimo, ?float $dias): string
    {
        if ($estoque <= 0) {
            return 'urgente';
        }

        if ($estoque <= $minimo) {
            return 'critico';
        }

        if ($estoque <= ($minimo + 2)) {
            return 'atencao';
        }

        return 'ok';
    }

    /**
     * Calcula quantidade sugerida de compra
     */
    protected function calcularReposicao(int $estoque, float $consumoDia, int $coberturaDias): int
    {
        if ($consumoDia <= 0) {
            return 0;
        }

        $necessario = ceil(($consumoDia * $coberturaDias) - $estoque);

        return max(0, (int)$necessario);
    }

    /**
     * Mensagem amigável para o usuário
     */
    protected function gerarMensagem(string $nivel, ?float $dias): string
    {
        $d = ($dias !== null) ? (int)ceil($dias) : null;

        return match ($nivel) {
            'urgente' => 'Urgente: estoque zerado ou risco imediato.',
            'critico' => $d ? "Crítico: previsão de falta em cerca de {$d} dias." : 'Crítico: abaixo do mínimo.',
            'atencao' => 'Atenção: próximo do mínimo, monitore.',
            default   => 'Estoque saudável.',
        };
    }
}
