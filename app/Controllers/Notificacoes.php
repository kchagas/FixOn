<?php

namespace App\Controllers;

use App\Models\NotificacaoModel;

class Notificacoes extends BaseController
{
    public function index()
    {
        $empresaId = session()->get('empresa_id');

        $model = new NotificacaoModel();

        $notificacoes = $model
            ->where('empresa_id', $empresaId)
            ->orderBy('created_at','DESC')
            ->findAll();

        return view('notificacoes/index', [
            'title' => 'Notificações',
            'notificacoes' => $notificacoes
        ]);
    }
}
