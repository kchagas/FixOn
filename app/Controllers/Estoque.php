<?php

namespace App\Controllers;

use App\Models\MovimentacaoModel;
use App\Models\PecaModel;
use App\Models\UserModel;


use Dompdf\Dompdf;
use Dompdf\Options;

class Estoque extends BaseController
{
    /**
     * ==========================================================
     * RELATÓRIO AVANÇADO DE MOVIMENTAÇÕES DE ESTOQUE
     *
     * Funcionalidades:
     * - Filtro por peça
     * - Filtro por tipo (entrada, saída, ajuste)
     * - Filtro por usuário
     * - Filtro por período (data início / fim)
     * - Retorno com JOIN para exibir nome da peça e do usuário
     * ==========================================================
     */
    public function relatorioMov()
    {
        // ------------------------------------------------------
        // Models utilizados
        // ------------------------------------------------------
        $movModel  = new MovimentacaoModel();
        $pecaModel = new PecaModel();
        $userModel = new UserModel();

        // ------------------------------------------------------
        // CAPTURA DOS FILTROS (via GET)
        // ------------------------------------------------------
        $tipo       = $this->request->getGet('tipo');          // entrada | saida | ajuste
        $pecaId     = $this->request->getGet('peca_id');       // ID da peça
        $usuarioId  = $this->request->getGet('usuario_id');    // ID do usuário
        $dataInicio = $this->request->getGet('data_inicio');   // yyyy-mm-dd
        $dataFim    = $this->request->getGet('data_fim');      // yyyy-mm-dd

        // ------------------------------------------------------
        // QUERY BASE DO RELATÓRIO
        // ------------------------------------------------------
        $builder = $movModel
            ->select('
                movimentacoes_estoque.*,
                pecas.nome AS nome_peca,
                pecas.sku,
                pecas.estoque_atual,
                pecas.estoque_minimo,
                usuarios.nome AS nome_usuario
            ')
            ->join('pecas', 'pecas.id = movimentacoes_estoque.peca_id')
            ->join('usuarios', 'usuarios.id = movimentacoes_estoque.usuario_id', 'left')
            ->orderBy('movimentacoes_estoque.created_at', 'DESC');

        // ------------------------------------------------------
        // APLICAÇÃO DOS FILTROS
        // ------------------------------------------------------

        /**
         * FILTRO POR TIPO
         * Agora aceita:
         * - entrada
         * - saida
         * - ajuste
         *
         * IMPORTANTE:
         * O erro anterior estava aqui, pois "ajuste" não era aceito.
         */
        if (!empty($tipo) && in_array($tipo, ['entrada', 'saida', 'ajuste'], true)) {
            $builder->where('movimentacoes_estoque.tipo', $tipo);
        }

        /**
         * FILTRO POR PEÇA
         */
        if (!empty($pecaId)) {
            $builder->where('movimentacoes_estoque.peca_id', (int)$pecaId);
        }

        /**
         * FILTRO POR USUÁRIO
         */
        if (!empty($usuarioId)) {
            $builder->where('movimentacoes_estoque.usuario_id', (int)$usuarioId);
        }

        /**
         * FILTRO POR DATA INICIAL
         * >= data_inicio 00:00:00
         */
        if (!empty($dataInicio)) {
            $builder->where('movimentacoes_estoque.created_at >=', $dataInicio . ' 00:00:00');
        }

        /**
         * FILTRO POR DATA FINAL
         * <= data_fim 23:59:59
         */
        if (!empty($dataFim)) {
            $builder->where('movimentacoes_estoque.created_at <=', $dataFim . ' 23:59:59');
        }

        // ------------------------------------------------------
        // EXECUÇÃO DA QUERY
        // ------------------------------------------------------
        $movimentacoes = $builder->get()->getResultArray();

        // ------------------------------------------------------
        // DADOS PARA OS SELECTS DE FILTRO
        // ------------------------------------------------------
        $pecas    = $pecaModel->orderBy('nome', 'ASC')->findAll();
        $usuarios = $userModel->orderBy('nome', 'ASC')->findAll();

        // ------------------------------------------------------
        // ENVIO PARA A VIEW
        // ------------------------------------------------------
        return view('estoque/relatorio_mov', [
            'title'          => 'Relatório de Movimentações',
            'movimentacoes'  => $movimentacoes,
            'pecas'          => $pecas,
            'usuarios'       => $usuarios,

            // Mantém filtros selecionados na tela
            'filtro_tipo'    => $tipo,
            'filtro_peca'    => $pecaId,
            'filtro_usuario' => $usuarioId,
            'filtro_ini'     => $dataInicio,
            'filtro_fim'     => $dataFim,
        ]);
    }


    public function relatorioMovPdf()
{
    // ------------------------------------------------------
    // Model
    // ------------------------------------------------------
    $movModel = new MovimentacaoModel();

    // ------------------------------------------------------
    // Reaproveita os filtros (GET)
    // ------------------------------------------------------
    $tipo       = $this->request->getGet('tipo');
    $pecaId     = $this->request->getGet('peca_id');
    $usuarioId  = $this->request->getGet('usuario_id');
    $dataInicio = $this->request->getGet('data_inicio');
    $dataFim    = $this->request->getGet('data_fim');

    // ------------------------------------------------------
    // Query base
    // ------------------------------------------------------
    $builder = $movModel
        ->select('
            movimentacoes_estoque.*,
            pecas.nome AS nome_peca,
            pecas.sku,
            pecas.estoque_atual,
            pecas.estoque_minimo,
            usuarios.nome AS nome_usuario
        ')
        ->join('pecas', 'pecas.id = movimentacoes_estoque.peca_id')
        ->join('usuarios', 'usuarios.id = movimentacoes_estoque.usuario_id', 'left')
        ->orderBy('movimentacoes_estoque.created_at', 'DESC');

    if (!empty($tipo) && in_array($tipo, ['entrada', 'saida', 'ajuste'], true)) {
        $builder->where('movimentacoes_estoque.tipo', $tipo);
    }

    if (!empty($pecaId)) {
        $builder->where('movimentacoes_estoque.peca_id', (int)$pecaId);
    }

    if (!empty($usuarioId)) {
        $builder->where('movimentacoes_estoque.usuario_id', (int)$usuarioId);
    }

    if (!empty($dataInicio)) {
        $builder->where('movimentacoes_estoque.created_at >=', $dataInicio . ' 00:00:00');
    }

    if (!empty($dataFim)) {
        $builder->where('movimentacoes_estoque.created_at <=', $dataFim . ' 23:59:59');
    }

    $movimentacoes = $builder->get()->getResultArray();

    // ------------------------------------------------------
    // LOGO EM BASE64 (FEIJÃO COM ARROZ)
    // ------------------------------------------------------
    $logoPath = FCPATH . 'assets/img/logo-fixon-pdf.png';

    $logoBase64 = '';
    if (file_exists($logoPath)) {
        $imageData = file_get_contents($logoPath);
        $logoBase64 = 'data:image/png;base64,' . base64_encode($imageData);
    }

    // ------------------------------------------------------
    // DOMPDF
    // ------------------------------------------------------
    $options = new Options();
    $options->set('defaultFont', 'Helvetica');

    $dompdf = new Dompdf($options);

    $html = view('estoque/relatorio_pdf', [
        'movimentacoes' => $movimentacoes,
        'logoBase64'    => $logoBase64,
        'dataGeracao'   => date('d/m/Y H:i')
    ]);

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $dompdf->stream('relatorio-movimentacoes.pdf', [
        'Attachment' => false
    ]);
}
    
}
