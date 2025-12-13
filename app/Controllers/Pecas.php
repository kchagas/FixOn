<?php

namespace App\Controllers;

use App\Models\PecaModel;

class Pecas extends BaseController
{
    // LISTAGEM
    public function index()
    {
        $model = new PecaModel();

        $data['title'] = "Peças";
        $data['pecas'] = $model
            ->where('empresa_id', session()->get('empresa_id'))
            ->orderBy('id', 'DESC')
            ->findAll();

        return view('pecas/index', $data);
    }

    // FORMULÁRIO DE CADASTRO
    public function cadastrar()
    {
        $data['title'] = "Cadastrar Peça";
        return view('pecas/cadastrar', $data);
    }

    // SALVAR NOVA PEÇA
    public function salvar()
    {
        $model = new PecaModel();
        
        $post = [
            'nome'           => esc($this->request->getPost('nome')),
            'descricao'      => esc($this->request->getPost('descricao')),
            'sku'            => strtoupper(esc($this->request->getPost('sku'))),
            'unidade_medida' => esc($this->request->getPost('unidade_medida')),
            'estoque_minimo' => (int)$this->request->getPost('estoque_minimo'),
            'preco_custo'    => (float)$this->request->getPost('preco_custo'),
            'preco_venda'    => (float)$this->request->getPost('preco_venda'),
            'empresa_id'     => session()->get('empresa_id')
        ];

        if (!$model->save($post)) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        return redirect()->to('/pecas')->with('success', 'Peça cadastrada com sucesso!');
    }

    // FORMULÁRIO DE EDIÇÃO
    public function editar($id)
    {
        $model = new PecaModel();

        $data['title'] = "Editar Peça";
        $data['peca'] = $model
            ->where('empresa_id', session()->get('empresa_id'))
            ->where('id', $id)
            ->first();

        if (!$data['peca']) {
            return redirect()->to('/pecas')->with('error', 'Peça não encontrada.');
        }

        return view('pecas/editar', $data);
    }

    // SALVAR ALTERAÇÕES
   public function atualizar($id)
{
    $model = new PecaModel();

    // Regras de validação personalizadas
    $rules = [
        'nome'           => 'required|min_length[3]',
        'sku'            => "required|is_unique[pecas.sku,id,{$id}]",
        'unidade_medida' => 'required',
        'estoque_minimo' => 'required|integer',
        'preco_custo'    => 'decimal',
        'preco_venda'    => 'decimal'
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $post = [
        'id'             => $id,
        'nome'           => esc($this->request->getPost('nome')),
        'descricao'      => esc($this->request->getPost('descricao')),
        'sku'            => strtoupper(esc($this->request->getPost('sku'))),
        'unidade_medida' => esc($this->request->getPost('unidade_medida')),
        'estoque_minimo' => (int)$this->request->getPost('estoque_minimo'),
        'preco_custo'    => (float)$this->request->getPost('preco_custo'),
        'preco_venda'    => (float)$this->request->getPost('preco_venda'),
    ];

    $model->save($post);

    return redirect()->to('/pecas')->with('success', 'Peça atualizada com sucesso!');
}

    // EXCLUIR PEÇA
    public function excluir($id)
{
    $pecaModel = new PecaModel();

    try {
        $pecaModel->delete($id);

        return redirect()
            ->to('/pecas')
            ->with('success', 'Peça excluída com sucesso.');

    } catch (\Exception $e) {

        // Verifica se é erro de integridade (FK - código 1451)
        if (strpos($e->getMessage(), '1451') !== false) {

            return redirect()
                ->to('/pecas')
                ->with('erro_exclusao', 'Esta peça já possui movimentações no estoque e não pode ser excluída.');
        }

        // Outros erros genéricos
        return redirect()
            ->to('/pecas')
            ->with('erro_exclusao', 'Erro ao excluir a peça.');
    }
}

    // DETALHES DA PEÇA
// DETALHES DA PEÇA
public function detalhes($id)
{
    $pecaModel = new \App\Models\PecaModel();
    $movModel  = new \App\Models\MovimentacaoModel();

    // --------------------------------------------------
    // BUSCA DA PEÇA
    // --------------------------------------------------
    $peca = $pecaModel->find($id);

    if (!$peca) {
        return redirect()->to('/pecas')->with('error', 'Peça não encontrada.');
    }

    // --------------------------------------------------
    // RESUMO DE MOVIMENTAÇÕES
    // --------------------------------------------------

    // Total de entradas
    $totalEntradas = $movModel
        ->where('peca_id', $id)
        ->where('tipo', 'entrada')
        ->selectSum('quantidade')
        ->first()['quantidade'] ?? 0;

    // Total de saídas
    $totalSaidas = $movModel
        ->where('peca_id', $id)
        ->where('tipo', 'saida')
        ->selectSum('quantidade')
        ->first()['quantidade'] ?? 0;

    // Total de ajustes
    $totalAjustes = $movModel
        ->where('peca_id', $id)
        ->where('tipo', 'ajuste')
        ->selectSum('quantidade')
        ->first()['quantidade'] ?? 0;

    // --------------------------------------------------
    // HISTÓRICO DA PEÇA
    // --------------------------------------------------
    $movimentacoes = $movModel
        ->select('movimentacoes_estoque.*, usuarios.nome AS nome_usuario')
        ->join('usuarios', 'usuarios.id = movimentacoes_estoque.usuario_id', 'left')
        ->where('peca_id', $id)
        ->orderBy('created_at', 'DESC')
        ->findAll();

    // --------------------------------------------------
    // STATUS DO ESTOQUE (INTELIGENTE)
    // --------------------------------------------------
    if ($peca['estoque_atual'] == 0) {
        $status = [
            'label'   => 'Estoque Zerado',
            'class'   => 'danger',
            'mensagem'=> 'Atenção! Esta peça está com estoque zerado.'
        ];
    } elseif ($peca['estoque_atual'] <= $peca['estoque_minimo']) {
        $status = [
            'label'   => 'Abaixo do Mínimo',
            'class'   => 'warning',
            'mensagem'=> 'Estoque abaixo do mínimo definido.'
        ];
    } else {
        $status = [
            'label'   => 'Estoque Saudável',
            'class'   => 'success',
            'mensagem'=> 'Estoque dentro do nível ideal.'
        ];
    }

    // --------------------------------------------------
    // ENVIO PARA A VIEW
    // --------------------------------------------------
    return view('pecas/detalhes', [
        'title'         => 'Detalhes da Peça',
        'peca'          => $peca,
        'totalEntradas' => $totalEntradas,
        'totalSaidas'   => $totalSaidas,
        'totalAjustes'  => $totalAjustes,
        'movimentacoes' => $movimentacoes,
        'status'        => $status
    ]);
}


}
