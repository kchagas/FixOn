<?php

namespace App\Controllers\Financeiro;

use App\Controllers\BaseController;
use App\Models\ContaPagarModel;

/**
 * ==========================================================
 * CONTROLLER: ContasPagar
 * ----------------------------------------------------------
 * Gerencia:
 * - Listagem geral
 * - Filtros (vencendo / vencidas)
 * - CRUD de despesas
 * ==========================================================
 */
class ContasPagar extends BaseController
{
    protected ContaPagarModel $model;

    public function __construct()
    {
        // Instancia o model
        $this->model = new ContaPagarModel();
    }

    /**
     * ------------------------------------------------------
     * LISTAGEM + FILTROS
     * ------------------------------------------------------
     */
   /**
 * ------------------------------------------------------
 * LISTAGEM DE CONTAS A PAGAR (COM FILTROS)
 * ------------------------------------------------------
 */
public function index()
{
    // Empresa logada (SaaS)
    $empresaId = session()->get('empresa_id');

    // Captura filtros via GET
    $filtros = [
        'status'       => $this->request->getGet('status'),
        'descricao'    => $this->request->getGet('descricao'),
        'data_inicio'  => $this->request->getGet('data_inicio'),
        'data_fim'     => $this->request->getGet('data_fim'),
        'order'        => $this->request->getGet('order') ?? 'data_vencimento',
        'dir'          => $this->request->getGet('dir') ?? 'asc',
    ];

    // Busca no model aplicando filtros
    $contas = $this->model->filtrarContas($empresaId, $filtros);

    // Retorna a view
    return view('financeiro/contas_pagar/index', [
        'title'   => 'Contas a Pagar',
        'contas'  => $contas,
        'filtros' => $filtros, // útil para manter estado no form
    ]);
}


    /**
     * ------------------------------------------------------
     * FORMULÁRIO NOVA DESPESA
     * ------------------------------------------------------
     */
    public function create()
    {
        return view('financeiro/contas_pagar/create', [
            'title' => 'Nova Despesa',
        ]);
    }

    /**
     * ------------------------------------------------------
     * SALVAR DESPESA
     * ------------------------------------------------------
     */
    public function store()
    {
        // Monta payload
        $data = [
            'empresa_id'      => session()->get('empresa_id'),
            'descricao'       => $this->request->getPost('descricao'),
            'valor'           => $this->request->getPost('valor'),
            'data_vencimento' => $this->request->getPost('data_vencimento'),
            'observacoes'     => $this->request->getPost('observacoes'),
        ];

        // Valida e salva
        if (!$this->model->save($data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->model->errors());
        }

        return redirect()->to('/financeiro/contas-pagar')
            ->with('success', 'Despesa lançada com sucesso.');
    }

    /**
     * ------------------------------------------------------
     * EDITAR DESPESA
     * ------------------------------------------------------
     */
    public function edit($id)
    {
        $conta = $this->model->find($id);

        if (!$conta) {
            return redirect()->to('/financeiro/contas-pagar');
        }

        return view('financeiro/contas_pagar/edit', [
            'title' => 'Editar Despesa',
            'conta' => $conta,
        ]);
    }

    /**
     * ------------------------------------------------------
     * ATUALIZAR / PAGAR
     * ------------------------------------------------------
     */
    public function update($id)
    {
        $data = [
            'id'             => $id,
            'descricao'      => $this->request->getPost('descricao'),
            'valor'          => $this->request->getPost('valor'),
            'data_vencimento'=> $this->request->getPost('data_vencimento'),
            'data_pagamento' => $this->request->getPost('data_pagamento'),
            'observacoes'    => $this->request->getPost('observacoes'),
        ];

        if (!$this->model->save($data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->model->errors());
        }

        return redirect()->to('/financeiro/contas-pagar')
            ->with('success', 'Despesa atualizada com sucesso.');
    }

    /**
     * ------------------------------------------------------
     * EXCLUIR
     * ------------------------------------------------------
     */
    public function delete($id)
    {
        $this->model->delete($id);

        return redirect()->to('/financeiro/contas-pagar')
            ->with('success', 'Despesa removida.');
    }
}
