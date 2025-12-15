<?php

namespace App\Controllers;

use App\Models\PecaModel;
use App\Models\MovimentacaoModel;

class EstoqueDashboard extends BaseController
{
    /**
     * ======================================================
     * DASHBOARD DE ESTOQUE (ERP)
     * - KPIs estratégicos
     * - Análise financeira
     * - Últimas movimentações
     * ======================================================
     */
    public function index($limit = 10)
    {
        $pecas = new PecaModel();
        $mov   = new MovimentacaoModel();

        // 🔐 Multiempresa (SaaS)
        $empresaId = session()->get('empresa_id');

        if (!$empresaId) {
            return redirect()->to('/login')
                ->with('error', 'Empresa não identificada.');
        }

        /* ==================================================
         * KPIs OPERACIONAIS
         * ================================================== */

        // Total de peças cadastradas
        $data['total_pecas'] = $pecas
            ->where('empresa_id', $empresaId)
            ->countAllResults();

        // Estoque crítico (<= mínimo)
        $data['criticos'] = $pecas
            ->where('empresa_id', $empresaId)
            ->where('estoque_atual <= estoque_minimo')
            ->countAllResults();

        // Próximo do mínimo (zona de atenção)
        $data['atencao'] = $pecas
            ->where('empresa_id', $empresaId)
            ->where('estoque_atual > estoque_minimo')
            ->where('estoque_atual <= estoque_minimo + 2')
            ->countAllResults();

        // Peças zeradas
        $data['pecas_zeradas'] = $pecas
            ->where('empresa_id', $empresaId)
            ->where('estoque_atual', 0)
            ->countAllResults();

        /* ==================================================
         * KPIs FINANCEIROS
         * ================================================== */

        // 💰 Valor em estoque (custo)
        $valorCusto = $pecas
            ->select('SUM(estoque_atual * preco_custo) AS total')
            ->where('empresa_id', $empresaId)
            ->first();

        $data['valor_estoque'] = $valorCusto['total'] ?? 0;

        // 💵 Valor potencial de venda (NOVO KPI)
        $valorVenda = $pecas
            ->select('SUM(estoque_atual * preco_venda) AS total')
            ->where('empresa_id', $empresaId)
            ->first();

        $data['valor_potencial_venda'] = $valorVenda['total'] ?? 0;

        /* ==================================================
         * CONTROLE DE LIMITE
         * ================================================== */
        $data['limite_atual'] = $limit;

        /* ==================================================
         * ÚLTIMAS MOVIMENTAÇÕES
         * ================================================== */
        $data['ultimas_mov'] = $mov
            ->select('
                movimentacoes_estoque.*,
                pecas.nome AS nome_peca,
                pecas.sku,
                pecas.estoque_atual,
                pecas.estoque_minimo
            ')
            ->join('pecas', 'pecas.id = movimentacoes_estoque.peca_id')
            ->where('pecas.empresa_id', $empresaId)
            ->orderBy('movimentacoes_estoque.id', 'DESC')
            ->limit($limit)
            ->findAll();

        return view('estoque/dashboard', $data);
    }
}
