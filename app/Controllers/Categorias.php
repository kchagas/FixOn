<?php

namespace App\Controllers;

use App\Models\CategoriaModel;

class Categorias extends BaseController
{
    /**
     * ======================================================
     * LISTAGEM DE CATEGORIAS
     * URL: GET /categorias
     * ======================================================
     */
    public function index()
    {
        $model = new CategoriaModel();

        // Empresa vinda da sessão (SaaS)
        $empresaId = session()->get('empresa_id');

        // Segurança básica
        if (!$empresaId) {
            return redirect()->to('/')->with('error', 'Empresa não identificada.');
        }

        $data = [
            'title'      => 'Categorias',
            'categorias' => $model
                ->where('empresa_id', $empresaId)
                ->orderBy('nome', 'ASC')
                ->findAll()
        ];

        return view('categorias/index', $data);
    }

    /**
     * ======================================================
     * FORMULÁRIO DE CADASTRO
     * URL: GET /categorias/cadastrar
     * ======================================================
     */
    public function cadastrar()
    {
        return view('categorias/cadastrar', [
            'title' => 'Cadastrar Categoria'
        ]);
    }

    /**
     * ======================================================
     * SALVAR NOVA CATEGORIA
     * URL: POST /categorias/salvar
     * ======================================================
     */
    public function salvar()
    {
       

        $model = new CategoriaModel();

        // Empresa da sessão (OBRIGATÓRIO)
        $empresaId = session()->get('empresa_id');

        if (!$empresaId) {
            return redirect()
                ->back()
                ->with('error', 'Empresa não identificada.');
        }

        // Dados vindos do formulário
        $data = [
            'nome'       => trim($this->request->getPost('nome')),
            'descricao'  => trim($this->request->getPost('descricao')),
            'empresa_id' => $empresaId,
            'ativo'      => 1
        ];

        /**
         * IMPORTANTE:
         * save() já dispara as validações do Model
         */
       if (!$model->save($data)) {
    dd([
        'dados_enviados' => $data,
        'erros_model'    => $model->errors(),
        'sql'            => $model->getLastQuery()
    ]);
}


        return redirect()
            ->to('/categorias')
            ->with('success', 'Categoria cadastrada com sucesso!');
    }

    /**
     * ======================================================
     * EXCLUIR CATEGORIA
     * URL: GET /categorias/excluir/{id}
     * ======================================================
     */
    public function excluir($id)
    {
        $model = new CategoriaModel();

        $model->delete($id);

        return redirect()
            ->to('/categorias')
            ->with('success', 'Categoria excluída com sucesso.');
    }

    /**
 * ======================================================
 * FORMULÁRIO DE EDIÇÃO
 * URL: GET /categorias/editar/{id}
 * ======================================================
 */
public function editar($id)
{
    $model = new CategoriaModel();
    $empresaId = session()->get('empresa_id');

    $categoria = $model
        ->where('id', $id)
        ->where('empresa_id', $empresaId)
        ->first();

    if (!$categoria) {
        return redirect()
            ->to('/categorias')
            ->with('error', 'Categoria não encontrada.');
    }

    return view('categorias/editar', [
        'title'     => 'Editar Categoria',
        'categoria' => $categoria
    ]);
}

/**
 * ======================================================
 * ATUALIZAR CATEGORIA
 * URL: POST /categorias/atualizar/{id}
 * ======================================================
 */
public function atualizar($id)
{
    $model = new CategoriaModel();
    $empresaId = session()->get('empresa_id');

    $data = [
        'id'         => $id,
        'nome'       => trim($this->request->getPost('nome')),
        'descricao'  => trim($this->request->getPost('descricao')),
        'empresa_id' => $empresaId
    ];

    if (!$model->save($data)) {
        return redirect()
            ->back()
            ->withInput()
            ->with('errors', $model->errors());
    }

    return redirect()
        ->to('/categorias')
        ->with('success', 'Categoria atualizada com sucesso!');
}
    /**
     * ======================================================
     * DESATIVAR CATEGORIA
     * URL: GET /categorias/desativar/{id}
     * ======================================================
     */
    /**
 * ======================================================
 * DESATIVAR CATEGORIA
 * - Não exclui do banco
 * - Bloqueia se houver peças vinculadas
 * ======================================================
 */
public function desativar($id)
{
    $categoriaModel = new \App\Models\CategoriaModel();
    $pecaModel      = new \App\Models\PecaModel();

    $empresaId = session()->get('empresa_id');

    // 🔒 Segurança: garante empresa correta
    $categoria = $categoriaModel
        ->where('id', $id)
        ->where('empresa_id', $empresaId)
        ->first();

    if (!$categoria) {
        return redirect()
            ->to('/categorias')
            ->with('error', 'Categoria não encontrada.');
    }

    // 🚫 Verifica se existem peças vinculadas
    $temPecas = $pecaModel
        ->where('categoria_id', $id)
        ->countAllResults();

    if ($temPecas > 0) {
        return redirect()
            ->to('/categorias')
            ->with('error', 'Não é possível desativar esta categoria pois existem peças vinculadas.');
    }

    // ✅ Desativação lógica
    $categoriaModel->update($id, [
        'ativo' => 0
    ]);

    return redirect()
        ->to('/categorias')
        ->with('success', 'Categoria desativada com sucesso.');
}


    /**
 * ======================================================
 * ATIVAR CATEGORIA
 * URL: GET /categorias/ativar/{id}
 * ======================================================
 */
public function ativar($id)
{
    $model = new CategoriaModel();

    // Empresa vinda da sessão (segurança SaaS)
    $empresaId = session()->get('empresa_id');

    $categoria = $model
        ->where('id', $id)
        ->where('empresa_id', $empresaId)
        ->first();

    if (!$categoria) {
        return redirect()
            ->to('/categorias')
            ->with('error', 'Categoria não encontrada.');
    }

    // Reativa a categoria
    $model->update($id, ['ativo' => 1]);

    return redirect()
        ->to('/categorias')
        ->with('success', 'Categoria ativada com sucesso.');
}



}
