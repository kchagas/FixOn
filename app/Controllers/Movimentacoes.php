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

        $peca_id   = $this->request->getPost('peca_id');
        $qtd       = (int)$this->request->getPost('quantidade');
        $motivo    = $this->request->getPost('motivo');

        if ($qtd <= 0) {
            return redirect()->back()->with('error', 'Quantidade inválida.');
        }

        // registra movimentação
        $movModel->insert([
            'peca_id'   => $peca_id,
            'tipo'      => 'entrada',
            'quantidade'=> $qtd,
            'motivo'    => $motivo,
            'usuario_id'=> session()->get('id'),
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

        $peca_id   = $this->request->getPost('peca_id');
        $qtd       = (int)$this->request->getPost('quantidade');
        $motivo    = $this->request->getPost('motivo');

        $peca = $pecaModel->find($peca_id);

        if ($qtd <= 0 || $qtd > $peca['estoque_atual']) {
            return redirect()->back()->with('error', 'Quantidade inválida ou maior que estoque disponível.');
        }

        // registra movimentação
        $movModel->insert([
            'peca_id'   => $peca_id,
            'tipo'      => 'saida',
            'quantidade'=> $qtd,
            'motivo'    => $motivo,
            'usuario_id'=> session()->get('id'),
        ]);

        // atualiza estoque
        $pecaModel->update($peca_id, [
            'estoque_atual' => $peca['estoque_atual'] - $qtd
        ]);

        return redirect()->to('/pecas')->with('success', 'Saída registrada com sucesso!');
    }
}
