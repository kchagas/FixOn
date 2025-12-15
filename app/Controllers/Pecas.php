<?php

namespace App\Controllers;

use App\Models\PecaModel;
use App\Models\CategoriaModel;
use App\Models\MovimentacaoModel;

class Pecas extends BaseController
{
    /**
     * ======================================================
     * LISTAGEM DE PEÇAS
     * - Suporta filtros vindos da Dashboard (?status=...)
     * - Sempre carrega categorias (evita erro na view)
     * ======================================================
     */
   public function index()
{
    $model = new PecaModel();
    $categoriaModel = new \App\Models\CategoriaModel();

    $empresaId = session()->get('empresa_id');
    if (!$empresaId) {
        return redirect()->to('/login')->with('error', 'Empresa não identificada.');
    }

    // Filtros
    $status           = $this->request->getGet('status');
    $filtroCategoria  = $this->request->getGet('categoria_id');
    $busca            = $this->request->getGet('q');

    /**
     * ==================================================
     * QUERY BASE COM JOIN DE CATEGORIA
     * ==================================================
     */
    $builder = $model
        ->select('pecas.*, categorias.nome AS categoria_nome')
        ->join('categorias', 'categorias.id = pecas.categoria_id', 'left')
        ->where('pecas.empresa_id', $empresaId);

    /**
     * ==================================================
     * FILTRO POR STATUS (DASHBOARD)
     * ==================================================
     */
    switch ($status) {
        case 'critico':
            $builder->where('estoque_atual <= estoque_minimo');
            break;

        case 'atencao':
            $builder->where('estoque_atual > estoque_minimo')
                    ->where('estoque_atual <= estoque_minimo + 2');
            break;

        case 'zerado':
            $builder->where('estoque_atual', 0);
            break;
    }

    /**
     * ==================================================
     * FILTRO POR CATEGORIA
     * ==================================================
     */
    if (!empty($filtroCategoria)) {
        $builder->where('pecas.categoria_id', $filtroCategoria);
    }

    /**
     * ==================================================
     * BUSCA POR SKU OU NOME
     * ==================================================
     */
    if (!empty($busca)) {
        $builder->groupStart()
            ->like('pecas.nome', $busca)
            ->orLike('pecas.sku', $busca)
            ->groupEnd();
    }

    $pecas = $builder
        ->orderBy('pecas.nome', 'ASC')
        ->findAll();

    return view('pecas/index', [
        'title'            => 'Peças',
        'pecas'            => $pecas,
        'categorias'       => $categoriaModel
                                ->where('empresa_id', $empresaId)
                                ->where('ativo', 1)
                                ->orderBy('nome', 'ASC')
                                ->findAll(),
        'filtro_categoria' => $filtroCategoria,
        'filtro_status'    => $status,
        'busca'            => $busca
    ]);
}
    /**
     * ======================================================
     * FORMULÁRIO DE CADASTRO
     * ======================================================
     */
    public function cadastrar()
    {
        $categoriaModel = new CategoriaModel();

        return view('pecas/cadastrar', [
            'title'      => 'Cadastrar Peça',
            'categorias' => $categoriaModel
                ->where('empresa_id', session()->get('empresa_id'))
                ->where('ativo', 1)
                ->orderBy('nome', 'ASC')
                ->findAll()
        ]);
    }

    /**
     * ======================================================
     * SALVAR NOVA PEÇA
     * ======================================================
     */
    public function salvar()
    {
        $model = new PecaModel();

        $post = [
            'nome'           => esc($this->request->getPost('nome')),
            'descricao'      => esc($this->request->getPost('descricao')),
            'sku'            => strtoupper(esc($this->request->getPost('sku'))),
            'unidade_medida' => esc($this->request->getPost('unidade_medida')),
            'categoria_id'   => (int) $this->request->getPost('categoria_id'),
            'estoque_minimo' => (int) $this->request->getPost('estoque_minimo'),
            'preco_custo'    => (float) $this->request->getPost('preco_custo'),
            'preco_venda'    => (float) $this->request->getPost('preco_venda'),
            'empresa_id'     => session()->get('empresa_id')
        ];

        if (!$model->save($post)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $model->errors());
        }

        return redirect()->to('/pecas')
            ->with('success', 'Peça cadastrada com sucesso!');
    }

    /**
     * ======================================================
     * FORMULÁRIO DE EDIÇÃO
     * ======================================================
     */
    public function editar($id)
    {
        $model = new PecaModel();

        $peca = $model
            ->where('empresa_id', session()->get('empresa_id'))
            ->where('id', $id)
            ->first();

        if (!$peca) {
            return redirect()->to('/pecas')
                ->with('error', 'Peça não encontrada.');
        }

        return view('pecas/editar', [
            'title' => 'Editar Peça',
            'peca'  => $peca
        ]);
    }

    /**
     * ======================================================
     * ATUALIZAR PEÇA
     * ======================================================
     */
    public function atualizar($id)
    {
        $model = new PecaModel();

        $rules = [
            'nome'           => 'required|min_length[3]',
            'sku'            => "required|is_unique[pecas.sku,id,{$id}]",
            'unidade_medida' => 'required',
            'estoque_minimo' => 'required|integer',
            'preco_custo'    => 'decimal',
            'preco_venda'    => 'decimal'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $post = [
            'id'             => $id,
            'nome'           => esc($this->request->getPost('nome')),
            'descricao'      => esc($this->request->getPost('descricao')),
            'sku'            => strtoupper(esc($this->request->getPost('sku'))),
            'unidade_medida' => esc($this->request->getPost('unidade_medida')),
            'estoque_minimo' => (int) $this->request->getPost('estoque_minimo'),
            'preco_custo'    => (float) $this->request->getPost('preco_custo'),
            'preco_venda'    => (float) $this->request->getPost('preco_venda'),
        ];

        $model->save($post);

        return redirect()->to('/pecas')
            ->with('success', 'Peça atualizada com sucesso!');
    }

    /**
     * ======================================================
     * EXCLUIR PEÇA (com proteção por FK)
     * ======================================================
     */
    public function excluir($id)
    {
        $model = new PecaModel();

        try {
            $model->delete($id);

            return redirect()->to('/pecas')
                ->with('success', 'Peça excluída com sucesso.');

        } catch (\Exception $e) {

            if (strpos($e->getMessage(), '1451') !== false) {
                return redirect()->to('/pecas')
                    ->with('erro_exclusao', 'Esta peça possui movimentações e não pode ser excluída.');
            }

            return redirect()->to('/pecas')
                ->with('erro_exclusao', 'Erro ao excluir a peça.');
        }
    }

    /**
     * ======================================================
     * DETALHES DA PEÇA
     * ======================================================
     */
    public function detalhes($id)
    {
        $pecaModel = new PecaModel();
        $movModel  = new MovimentacaoModel();

        // Busca peça
        $peca = $pecaModel->find($id);

        if (!$peca) {
            return redirect()->to('/pecas')
                ->with('error', 'Peça não encontrada.');
        }

        /**
         * -------------------------------
         * RESUMO DE MOVIMENTAÇÕES
         * -------------------------------
         */
        $totalEntradas = $movModel
            ->where('peca_id', $id)
            ->where('tipo', 'entrada')
            ->selectSum('quantidade')
            ->first()['quantidade'] ?? 0;

        $totalSaidas = $movModel
            ->where('peca_id', $id)
            ->where('tipo', 'saida')
            ->selectSum('quantidade')
            ->first()['quantidade'] ?? 0;

        $totalAjustes = $movModel
            ->where('peca_id', $id)
            ->where('tipo', 'ajuste')
            ->selectSum('quantidade')
            ->first()['quantidade'] ?? 0;

        /**
         * -------------------------------
         * HISTÓRICO (GARANTE ARRAY)
         * -------------------------------
         */
        $movimentacoes = $movModel
            ->select('movimentacoes_estoque.*, usuarios.nome AS nome_usuario')
            ->join('usuarios', 'usuarios.id = movimentacoes_estoque.usuario_id', 'left')
            ->where('peca_id', $id)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $movimentacoes = $movimentacoes ?? [];

        /**
         * -------------------------------
         * STATUS INTELIGENTE
         * -------------------------------
         */
        if ($peca['estoque_atual'] == 0) {
            $status = [
                'label'    => 'Estoque Zerado',
                'class'    => 'danger',
                'mensagem' => 'Reposição urgente necessária.'
            ];
        } elseif ($peca['estoque_atual'] <= $peca['estoque_minimo']) {
            $status = [
                'label'    => 'Abaixo do Mínimo',
                'class'    => 'warning',
                'mensagem' => 'Estoque abaixo do mínimo.'
            ];
        } else {
            $status = [
                'label'    => 'Estoque OK',
                'class'    => 'success',
                'mensagem' => 'Estoque dentro do nível ideal.'
            ];
        }

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

    /**
     * ======================================================
     * EXPORTAÇÃO CSV
     * ======================================================
     */
    public function exportarCsv()
    {
        $model = new PecaModel();

        $pecas = $model
            ->where('empresa_id', session()->get('empresa_id'))
            ->findAll();

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename=pecas.csv');

        $out = fopen('php://output', 'w');
        fputcsv($out, ['SKU', 'Nome', 'Estoque', 'Mínimo']);

        foreach ($pecas as $p) {
            fputcsv($out, [
                $p['sku'],
                $p['nome'],
                $p['estoque_atual'],
                $p['estoque_minimo']
            ]);
        }

        fclose($out);
        exit;
    }
}
