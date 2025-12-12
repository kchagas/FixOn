<?php

namespace App\Controllers;

use App\Models\PecaModel;

class Pecas extends BaseController
{
    /**
     * ======================================================
     *  TELA DE CADASTRO DE PEÇAS
     * ======================================================
     * Carrega apenas o formulário para cadastrar uma nova peça.
     */
    public function cadastrar()
    {
        // Título enviado para a view
        $data['title'] = "Cadastrar Peça";

        // Retorna a view localizada em app/Views/pecas/cadastrar.php
        return view('pecas/cadastrar', $data);
    }


    /**
     * ======================================================
     *  SALVAR PEÇA NO BANCO DE DADOS
     * ======================================================
     * Recebe os dados do formulário de cadastro e salva
     * no Banco MySQL com validação e segurança.
     */
    public function salvar()
    {
        // Instancia o Model responsável pela tabela pecas
        $pecaModel = new PecaModel();

        /**
         * Sanitização manual dos dados:
         * - esc() remove tags e caracteres perigosos (XSS)
         * - strtoupper() coloca o SKU em maiúsculas
         * - (int) força números inteiros
         * - (float) força valores monetários
         * - session()->get('empresa_id') garante o multi-tenant
         */
        $post = [
            'nome'           => esc($this->request->getPost('nome')),
            'descricao'      => esc($this->request->getPost('descricao')),
            'sku'            => strtoupper(esc($this->request->getPost('sku'))),
            'unidade_medida' => esc($this->request->getPost('unidade_medida')),
            'estoque_minimo' => (int)$this->request->getPost('estoque_minimo'),
            'preco_custo'    => (float)$this->request->getPost('preco_custo'),
            'preco_venda'    => (float)$this->request->getPost('preco_venda'),
            'empresa_id'     => session()->get('empresa_id') // Multi-tenant profissional
        ];

        /**
         * Validação automática:
         * O PecaModel contém validationRules + validationMessages
         * Model->save() faz:
         *   - insert ou update automático
         *   - validação
         *   - proteção de campos
         */
        if (!$pecaModel->save($post)) {

            // Se falhar: volta com os erros e mantém os dados preenchidos
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $pecaModel->errors());
        }

        /**
         * Se deu tudo certo: envia uma mensagem de sucesso
         * e mantém o usuário na mesma tela para cadastrar outra peça.
         */
        return redirect()
            ->to('/pecas/cadastrar')
            ->with('success', 'Peça cadastrada com sucesso!');
    }


    /**
     * ======================================================
     *  LISTAGEM DE TODAS AS PEÇAS
     * ======================================================
     * Exibe uma tabela com todas as peças cadastradas.
     * Filtra automaticamente pela empresa (multi-tenant).
     */
    public function index()
    {
        $model = new PecaModel();

        // Título da página
        $data['title'] = "Peças";

        // Busca apenas peças da empresa logada
        $data['pecas'] = $model
            ->where('empresa_id', session()->get('empresa_id'))
            ->findAll();

        // Retorna a lista em app/Views/pecas/index.php
        return view('pecas/index', $data);
    }
}

