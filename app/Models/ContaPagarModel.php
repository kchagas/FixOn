<?php

namespace App\Models; // Namespace padrão dos Models no CI4

use CodeIgniter\Model;

/**
 * ==========================================================
 * MODEL: ContaPagarModel
 * ----------------------------------------------------------
 * Responsável por:
 * - CRUD de despesas (Contas a Pagar)
 * - Isolamento por empresa (SaaS)
 * - Definição automática de status
 * - Fornecer dados para Dashboard Financeiro
 * ==========================================================
 */
class ContaPagarModel extends Model
{
    /**
     * ------------------------------------------------------
     * CONFIGURAÇÕES BÁSICAS
     * ------------------------------------------------------
     */

    // Nome da tabela no banco
    protected $table = 'contas_pagar';

    // Chave primária
    protected $primaryKey = 'id';

    // Retorno em array (facilita uso nas views)
    protected $returnType = 'array';

    /**
     * ------------------------------------------------------
     * CAMPOS PERMITIDOS (Mass Assignment Protection)
     * ------------------------------------------------------
     * Somente estes campos podem ser gravados via save()
     */
    protected $allowedFields = [
        'empresa_id',       // Empresa (SaaS)
        'descricao',        // Descrição da despesa
        'valor',            // Valor da conta
        'data_vencimento',  // Data de vencimento
        'data_pagamento',   // Data de pagamento (nullable)
        'status',           // aberto | pago | atrasado (definido automaticamente)
        'observacoes',      // Observações livres
        'created_at',       // Timestamp criação
        'updated_at',       // Timestamp atualização
    ];

    /**
     * ------------------------------------------------------
     * TIMESTAMPS AUTOMÁTICOS
     * ------------------------------------------------------
     */
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * ------------------------------------------------------
     * REGRAS DE VALIDAÇÃO
     * ------------------------------------------------------
     * IMPORTANTE:
     * ❌ NÃO validamos "status" aqui
     * ✔️ O status é definido automaticamente nos callbacks
     */
    protected $validationRules = [
        'empresa_id'      => 'required|integer',
        'descricao'       => 'required|min_length[3]',
        'valor'           => 'required|decimal',
        'data_vencimento' => 'required|valid_date',
    ];

    /**
     * ------------------------------------------------------
     * CALLBACKS (executados automaticamente pelo CI4)
     * ------------------------------------------------------
     */
    protected $beforeInsert = ['definirStatusAntesDeInserir'];
    protected $beforeUpdate = ['definirStatusAntesDeAtualizar'];

    /**
     * ------------------------------------------------------
     * DEFINE STATUS AUTOMATICAMENTE
     * ------------------------------------------------------
     * Regras:
     * - Se tem data_pagamento → PAGO
     * - Se venceu e não foi pago → ATRASADO
     * - Caso contrário → ABERTO
     */
    public function atualizarStatusAutomatico(array &$data): void
    {
        // Caso a conta já tenha sido paga
        if (!empty($data['data_pagamento'])) {
            $data['status'] = 'pago';
            return;
        }

        // Caso tenha vencimento e esteja atrasada
        if (!empty($data['data_vencimento'])) {
            $hoje = date('Y-m-d');

            if ($data['data_vencimento'] < $hoje) {
                $data['status'] = 'atrasado';
                return;
            }
        }

        // Caso padrão
        $data['status'] = 'aberto';
    }

    /**
     * ------------------------------------------------------
     * CALLBACK BEFORE INSERT
     * ------------------------------------------------------
     */
    protected function definirStatusAntesDeInserir(array $data): array
    {
        if (isset($data['data'])) {
            $this->atualizarStatusAutomatico($data['data']);
        }

        return $data;
    }

    /**
     * ------------------------------------------------------
     * CALLBACK BEFORE UPDATE
     * ------------------------------------------------------
     */
    protected function definirStatusAntesDeAtualizar(array $data): array
    {
        if (isset($data['data'])) {
            $this->atualizarStatusAutomatico($data['data']);
        }

        return $data;
    }

    /**
     * ------------------------------------------------------
     * LISTAGEM PADRÃO POR EMPRESA (SaaS)
     * ------------------------------------------------------
     */
    public function listarPorEmpresa(int $empresaId): array
    {
        return $this->where('empresa_id', $empresaId)
            ->orderBy('data_vencimento', 'ASC')
            ->findAll();
    }

    /**
     * ======================================================
     * DASHBOARD FINANCEIRO — KPIs
     * ======================================================
     */
    public function getResumoDashboard(int $empresaId): array
    {
        $hoje   = date('Y-m-d');
        $limite = date('Y-m-d', strtotime('+7 days'));

        // Total em aberto
        $totalAberto = $this->selectSum('valor', 'total')
            ->where('empresa_id', $empresaId)
            ->where('status', 'aberto')
            ->first()['total'] ?? 0;

        // Total vencido
        $totalVencido = $this->selectSum('valor', 'total')
            ->where('empresa_id', $empresaId)
            ->where('status', 'atrasado')
            ->first()['total'] ?? 0;

        // Total pago
        $totalPago = $this->selectSum('valor', 'total')
            ->where('empresa_id', $empresaId)
            ->where('status', 'pago')
            ->first()['total'] ?? 0;

        // Quantidade vencendo em até 7 dias
        $qtdVencendo = $this->where('empresa_id', $empresaId)
            ->where('status', 'aberto')
            ->where('data_vencimento >=', $hoje)
            ->where('data_vencimento <=', $limite)
            ->countAllResults();

        return [
            'total_aberto'    => (float) $totalAberto,
            'total_vencido'   => (float) $totalVencido,
            'total_pago'      => (float) $totalPago,
            'qtd_vencendo_7d' => (int) $qtdVencendo,
        ];
    }

    /**
     * ------------------------------------------------------
     * LISTA CONTAS VENCENDO EM X DIAS
     * ------------------------------------------------------
     */
    public function listarVencendo(int $empresaId, int $dias = 7): array
    {
        $hoje   = date('Y-m-d');
        $limite = date('Y-m-d', strtotime("+{$dias} days"));

        return $this->where('empresa_id', $empresaId)
            ->where('status', 'aberto')
            ->where('data_vencimento >=', $hoje)
            ->where('data_vencimento <=', $limite)
            ->orderBy('data_vencimento', 'ASC')
            ->findAll();
    }

    /**
     * ------------------------------------------------------
     * LISTA CONTAS VENCIDAS
     * ------------------------------------------------------
     */
    /**
 * ======================================================
 * LISTAR CONTAS VENCIDAS (ATRASADAS)
 * ------------------------------------------------------
 * Regras:
 * - status = atrasado
 * - NÃO pode ter data de pagamento
 * ======================================================
 */
public function listarVencidas(int $empresaId, int $limite = 10): array
{
    return $this
        ->where('empresa_id', $empresaId)          // Isolamento SaaS
        ->where('status', 'atrasado')               // Somente atrasadas
        ->where('data_pagamento', null)             // Nunca pagas
        ->orderBy('data_vencimento', 'ASC')         // Mais antigas primeiro
        ->findAll($limite);
}

/**
 * ======================================================
 * FILTRAR CONTAS A PAGAR (DINÂMICO)
 * ======================================================
 */
public function filtrarContas(int $empresaId, array $filtros): array
{
    // Inicia query base
    $builder = $this->where('empresa_id', $empresaId);

    /**
     * -----------------------------
     * FILTRO: STATUS
     * -----------------------------
     */
    if (!empty($filtros['status'])) {
        $builder->where('status', $filtros['status']);
    }

    /**
     * -----------------------------
     * FILTRO: DESCRIÇÃO (LIKE)
     * -----------------------------
     */
    if (!empty($filtros['descricao'])) {
        $builder->like('descricao', $filtros['descricao']);
    }

    /**
     * -----------------------------
     * FILTRO: DATA INICIAL
     * -----------------------------
     */
    if (!empty($filtros['data_inicio'])) {
        $builder->where('data_vencimento >=', $filtros['data_inicio']);
    }

    /**
     * -----------------------------
     * FILTRO: DATA FINAL
     * -----------------------------
     */
    if (!empty($filtros['data_fim'])) {
        $builder->where('data_vencimento <=', $filtros['data_fim']);
    }

    /**
     * -----------------------------
     * ORDENAÇÃO
     * -----------------------------
     */
    $orderPermitido = ['data_vencimento', 'valor'];

    $order = in_array($filtros['order'], $orderPermitido)
        ? $filtros['order']
        : 'data_vencimento';

    $dir = strtolower($filtros['dir']) === 'desc' ? 'DESC' : 'ASC';

    $builder->orderBy($order, $dir);

    // Executa query
    return $builder->findAll();
}


}
