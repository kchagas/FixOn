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
     * Recursos:
     * - Filtro por tipo (entrada | saída | ajuste)
     * - Filtro por peça
     * - Filtro por usuário
     * - Filtro por período
     * - Multiempresa (SaaS)
     * ==========================================================
     */
    public function relatorioMov()
    {
        /* ======================================================
         * SEGURANÇA SAAS
         * ====================================================== */
        $empresaId = session()->get('empresa_id');

        if (!$empresaId) {
            return redirect()->to('/login')
                ->with('error', 'Empresa não identificada.');
        }

        /* ======================================================
         * MODELS
         * ====================================================== */
        $movModel  = new MovimentacaoModel();
        $pecaModel = new PecaModel();
        $userModel = new UserModel();

        /* ======================================================
         * FILTROS (GET)
         * ====================================================== */
        $tipo       = $this->request->getGet('tipo');          // entrada | saida | ajuste
        $pecaId     = $this->request->getGet('peca_id');       // ID da peça
        $usuarioId  = $this->request->getGet('usuario_id');    // ID do usuário
        $dataInicio = $this->request->getGet('data_inicio');   // yyyy-mm-dd
        $dataFim    = $this->request->getGet('data_fim');      // yyyy-mm-dd

        /* ======================================================
         * QUERY BASE
         * ====================================================== */
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
            ->where('pecas.empresa_id', $empresaId)
            ->orderBy('movimentacoes_estoque.created_at', 'DESC');

        /* ======================================================
         * APLICAÇÃO DOS FILTROS
         * ====================================================== */

        // Tipo de movimentação
        if (!empty($tipo) && in_array($tipo, ['entrada', 'saida', 'ajuste'], true)) {
            $builder->where('movimentacoes_estoque.tipo', $tipo);
        }

        // Peça específica
        if (!empty($pecaId)) {
            $builder->where('movimentacoes_estoque.peca_id', (int)$pecaId);
        }

        // Usuário
        if (!empty($usuarioId)) {
            $builder->where('movimentacoes_estoque.usuario_id', (int)$usuarioId);
        }

        // Data inicial
        if (!empty($dataInicio)) {
            $builder->where('movimentacoes_estoque.created_at >=', $dataInicio . ' 00:00:00');
        }

        // Data final
        if (!empty($dataFim)) {
            $builder->where('movimentacoes_estoque.created_at <=', $dataFim . ' 23:59:59');
        }

        /* ======================================================
         * EXECUÇÃO
         * ====================================================== */
        $movimentacoes = $builder->get()->getResultArray();

        /* ======================================================
         * DADOS PARA FILTROS DA VIEW
         * ====================================================== */
        $pecas = $pecaModel
            ->where('empresa_id', $empresaId)
            ->orderBy('nome', 'ASC')
            ->findAll();

        $usuarios = $userModel
            ->orderBy('nome', 'ASC')
            ->findAll();

        /* ======================================================
         * VIEW
         * ====================================================== */
        return view('estoque/relatorio_mov', [
            'title'          => 'Relatório de Movimentações',
            'movimentacoes'  => $movimentacoes,
            'pecas'          => $pecas,
            'usuarios'       => $usuarios,

            // Mantém filtros ativos
            'filtro_tipo'    => $tipo,
            'filtro_peca'    => $pecaId,
            'filtro_usuario' => $usuarioId,
            'filtro_ini'     => $dataInicio,
            'filtro_fim'     => $dataFim,
        ]);
    }

    /**
     * ==========================================================
     * RELATÓRIO DE MOVIMENTAÇÕES EM PDF
     * (Reutiliza exatamente os mesmos filtros)
     * ==========================================================
     */
    public function relatorioMovPdf()
    {
        /* ======================================================
         * SEGURANÇA SAAS
         * ====================================================== */
        $empresaId = session()->get('empresa_id');

        if (!$empresaId) {
            return redirect()->to('/login');
        }

        $movModel = new MovimentacaoModel();

        /* ======================================================
         * FILTROS (GET)
         * ====================================================== */
        $tipo       = $this->request->getGet('tipo');
        $pecaId     = $this->request->getGet('peca_id');
        $usuarioId  = $this->request->getGet('usuario_id');
        $dataInicio = $this->request->getGet('data_inicio');
        $dataFim    = $this->request->getGet('data_fim');

        /* ======================================================
         * QUERY BASE
         * ====================================================== */
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
            ->where('pecas.empresa_id', $empresaId)
            ->orderBy('movimentacoes_estoque.created_at', 'DESC');

        /* ======================================================
         * APLICA FILTROS
         * ====================================================== */
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

        /* ======================================================
         * LOGO BASE64 (PDF)
         * ====================================================== */
        $logoPath = FCPATH . 'assets/img/logo-fixon-pdf.png';
        $logoBase64 = '';

        if (file_exists($logoPath)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }

        /* ======================================================
         * DOMPDF
         * ====================================================== */
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

    /**
 * ==========================================================
 * TELA DE REPOSIÇÃO DE ESTOQUE
 * ==========================================================
 */
public function repor($pecaId)
{
    $empresaId = session()->get('empresa_id');

    if (!$empresaId) {
        return redirect()->to('/login');
    }

    $pecaModel = new \App\Models\PecaModel();

    // Busca a peça da empresa
    $peca = $pecaModel
        ->where('empresa_id', $empresaId)
        ->where('id', $pecaId)
        ->first();

    if (!$peca) {
        return redirect()->to('/pecas')
            ->with('error', 'Peça não encontrada.');
    }

    // Verifica se é reposição urgente
    $urgente = $this->request->getGet('urgente');

    return view('estoque/repor', [
        'title'   => 'Repor Estoque',
        'peca'    => $peca,
        'urgente' => $urgente
    ]);
}

    /**
 * ==========================================================
 * PROCESSAR REPOSIÇÃO DE ESTOQUE
 * ==========================================================
 * - Registra movimentação (entrada)
 * - Atualiza estoque da peça
 * - Mantém histórico
 */
public function processarReposicao()
{
    $movModel  = new MovimentacaoModel();
    $pecaModel = new PecaModel();

    // ------------------------------------------------------
    // CAPTURA DOS DADOS DO FORMULÁRIO
    // ------------------------------------------------------
    $pecaId     = (int) $this->request->getPost('peca_id');
    $quantidade = (int) $this->request->getPost('quantidade');
    $motivo     = trim($this->request->getPost('motivo'));
    $usuarioId  = session()->get('user_id');

    // ------------------------------------------------------
    // VALIDAÇÕES DE SEGURANÇA
    // ------------------------------------------------------
    if (!$pecaId || $quantidade <= 0 || empty($motivo)) {
        return redirect()->back()
            ->with('error', 'Dados inválidos para reposição.');
    }

    // ------------------------------------------------------
    // BUSCA DA PEÇA
    // ------------------------------------------------------
    $peca = $pecaModel->find($pecaId);

    if (!$peca) {
        return redirect()->to('/pecas')
            ->with('error', 'Peça não encontrada.');
    }

    // ------------------------------------------------------
    // REGISTRA MOVIMENTAÇÃO (ENTRADA)
    // ------------------------------------------------------
    $movModel->insert([
        'peca_id'     => $pecaId,
        'tipo'        => 'entrada',
        'quantidade'  => $quantidade,
        'motivo'      => $motivo,
        'usuario_id'  => $usuarioId,
        'created_at'  => date('Y-m-d H:i:s')
    ]);

    // ------------------------------------------------------
    // ATUALIZA ESTOQUE DA PEÇA
    // ------------------------------------------------------
    $novoEstoque = $peca['estoque_atual'] + $quantidade;

    $pecaModel->update($pecaId, [
        'estoque_atual' => $novoEstoque
    ]);

    // ------------------------------------------------------
    // REDIRECIONAMENTO FINAL
    // ------------------------------------------------------
    return redirect()->to('/pecas')
        ->with('success', 'Reposição de estoque realizada com sucesso!');
}


}
