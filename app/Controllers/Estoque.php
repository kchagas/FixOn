<?php

namespace App\Controllers;

use App\Models\MovimentacaoModel;
use App\Models\PecaModel;
use App\Models\UserModel;

class Estoque extends BaseController
{
    // ... seus outros métodos (dashboard, etc.)

    /**
     * RELATÓRIO AVANÇADO DE MOVIMENTAÇÕES
     * - Filtros por peça, tipo, usuário, período
     * - Tabela com DataTables (busca, ordenação, paginação)
     */
    public function relatorioMov()
    {
        $movModel  = new MovimentacaoModel();
        $pecaModel = new PecaModel();
        $userModel = new UserModel();

        // ---- FILTROS (GET) ----
        $tipo       = $this->request->getGet('tipo');          // entrada / saida
        $pecaId     = $this->request->getGet('peca_id');       // id da peça
        $usuarioId  = $this->request->getGet('usuario_id');    // id do usuário
        $dataInicio = $this->request->getGet('data_inicio');   // yyyy-mm-dd
        $dataFim    = $this->request->getGet('data_fim');      // yyyy-mm-dd

        // Builder da consulta
        $builder = $movModel
            ->select('
                movimentacoes_estoque.*,
                pecas.nome  AS nome_peca,
                pecas.sku   AS sku,
                usuarios.nome AS nome_usuario,
                pecas.estoque_atual,
                pecas.estoque_minimo
            ')
            ->join('pecas', 'pecas.id = movimentacoes_estoque.peca_id')
            ->join('usuarios', 'usuarios.id = movimentacoes_estoque.usuario_id', 'left')
            ->orderBy('movimentacoes_estoque.created_at', 'DESC');

        // Aplica filtros dinamicamente
        if ($tipo && in_array($tipo, ['entrada', 'saida'])) {
            $builder->where('movimentacoes_estoque.tipo', $tipo);
        }

        if (!empty($pecaId)) {
            $builder->where('movimentacoes_estoque.peca_id', (int)$pecaId);
        }

        if (!empty($usuarioId)) {
            $builder->where('movimentacoes_estoque.usuario_id', (int)$usuarioId);
        }

        if (!empty($dataInicio)) {
            $builder->where('DATE(movimentacoes_estoque.created_at) >=', $dataInicio);
        }

        if (!empty($dataFim)) {
            $builder->where('DATE(movimentacoes_estoque.created_at) <=', $dataFim);
        }

        // Executa a query
        $movimentacoes = $builder->get()->getResultArray();

        // Listas para os selects de filtros
        $pecas    = $pecaModel->orderBy('nome', 'ASC')->findAll();
        $usuarios = $userModel->orderBy('nome', 'ASC')->findAll();

        // Envia dados para a view
        $data = [
            'title'          => 'Relatório de Movimentações',
            'movimentacoes'  => $movimentacoes,
            'pecas'          => $pecas,
            'usuarios'       => $usuarios,
            // mantém o valor selecionado nos filtros
            'filtro_tipo'    => $tipo,
            'filtro_peca'    => $pecaId,
            'filtro_usuario' => $usuarioId,
            'filtro_ini'     => $dataInicio,
            'filtro_fim'     => $dataFim,
        ];

        return view('estoque/relatorio_mov', $data);
    }
}
