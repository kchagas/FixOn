<?php

namespace App\Controllers;

use App\Models\PecaModel;
use App\Models\MovimentacaoModel;

class EstoqueDashboard extends BaseController
{
   public function index($limit = 10)
{
    $pecas = new PecaModel();
    $mov   = new MovimentacaoModel();

    // KPIs
    $data['total_pecas'] = $pecas->countAllResults();

    $data['criticos'] = $pecas
        ->where('estoque_atual <= estoque_minimo')
        ->countAllResults();

    $data['atencao'] = $pecas
        ->where('estoque_atual > estoque_minimo')
        ->where('estoque_atual <= estoque_minimo + 2')
        ->countAllResults();

    // Salvar limite atual para a view
    $data['limite_atual'] = $limit;

    // JOIN para buscar nome, sku e estoque
    $data['ultimas_mov'] = $mov
        ->select('movimentacoes_estoque.*, 
                  pecas.nome AS nome_peca, 
                  pecas.sku,
                  pecas.estoque_atual,
                  pecas.estoque_minimo')
        ->join('pecas', 'pecas.id = movimentacoes_estoque.peca_id')
        ->orderBy('movimentacoes_estoque.id', 'DESC')
        ->limit($limit)
        ->findAll();

    return view('estoque/dashboard', $data);
}

}
