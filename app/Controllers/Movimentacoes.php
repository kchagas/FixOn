<?php

namespace App\Controllers;

use App\Models\PecaModel;
use App\Models\MovimentacaoModel;

class Movimentacoes extends BaseController
{
    public function entrada()
    {
        $pecaModel = new PecaModel();
        $data['pecas'] = $pecaModel->findAll();

        return view('movimentacoes/entrada', $data);
    }

    public function salvarEntrada()
    {
        $movModel  = new MovimentacaoModel();
        $pecaModel = new PecaModel();

        $peca_id = $this->request->getPost('peca_id');
        $qtd     = (int)$this->request->getPost('quantidade');
        $motivo  = $this->request->getPost('motivo');

        if ($qtd <= 0) {
            return redirect()->back()->with('error', 'Quantidade inválida.');
        }

        // 🔥 ID do usuário CORRETO
        $usuarioId = session()->get('user_id');

        // registra movimentação
        $movModel->insert([
            'peca_id'    => $peca_id,
            'tipo'       => 'entrada',
            'quantidade' => $qtd,
            'motivo'     => $motivo,
            'usuario_id' => $usuarioId,
        ]);

        // atualiza estoque
        $peca = $pecaModel->find($peca_id);
        $pecaModel->update($peca_id, [
            'estoque_atual' => $peca['estoque_atual'] + $qtd
        ]);

        return redirect()->to('/pecas')->with('success', 'Entrada registrada com sucesso!');
    }

    public function saida()
    {
        $pecaModel = new PecaModel();
        $data['pecas'] = $pecaModel->findAll();

        return view('movimentacoes/saida', $data);
    }

    public function salvarSaida()
    {
        $movModel  = new MovimentacaoModel();
        $pecaModel = new PecaModel();

        $peca_id = $this->request->getPost('peca_id');
        $qtd     = (int)$this->request->getPost('quantidade');
        $motivo  = $this->request->getPost('motivo');

        $peca = $pecaModel->find($peca_id);

        if ($qtd <= 0 || $qtd > $peca['estoque_atual']) {
            return redirect()->back()->with('error', 'Quantidade inválida ou maior que o estoque disponível.');
        }

        // 🔥 ID do usuário CORRETO
        $usuarioId = session()->get('user_id');

        // registra movimentação
        $movModel->insert([
            'peca_id'    => $peca_id,
            'tipo'       => 'saida',
            'quantidade' => $qtd,
            'motivo'     => $motivo,
            'usuario_id' => $usuarioId,
        ]);

        // atualiza estoque
        $pecaModel->update($peca_id, [
            'estoque_atual' => $peca['estoque_atual'] - $qtd
        ]);

        return redirect()->to('/pecas')->with('success', 'Saída registrada com sucesso!');
    }

    // AJUSTAR ESTOQUE

    public function ajustarEstoque()
{
    $pecaModel = new \App\Models\PecaModel();
    $movModel  = new \App\Models\MovimentacaoModel();

    $pecaId        = $this->request->getPost('peca_id');
    $novoEstoque   = (int) $this->request->getPost('novo_estoque');
    $motivo        = trim($this->request->getPost('motivo'));

    $peca = $pecaModel->find($pecaId);

    if (!$peca) {
        return redirect()->back()->with('error', 'Peça não encontrada.');
    }

    if ($novoEstoque < 0) {
        return redirect()->back()->with('error', 'Estoque não pode ser negativo.');
    }

    if ($motivo === '') {
        return redirect()->back()->with('error', 'Motivo do ajuste é obrigatório.');
    }

    $estoqueAtual = (int) $peca['estoque_atual'];
    $diferenca    = $novoEstoque - $estoqueAtual;

    if ($diferenca === 0) {
        return redirect()->back()->with('error', 'Nenhuma alteração no estoque.');
    }

    // registra movimentação
    $movModel->insert([
        'peca_id'    => $pecaId,
        'tipo'       => 'ajuste',
        'quantidade' => abs($diferenca),
        'motivo'     => 'Ajuste: ' . $motivo,
        'usuario_id' => session()->get('id'),
    ]);

    // atualiza estoque
    $pecaModel->update($pecaId, [
        'estoque_atual' => $novoEstoque
    ]);

    return redirect()->to('/pecas/detalhes/' . $pecaId)
        ->with('success', 'Ajuste de estoque realizado com sucesso.');
}

}
