<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificacaoModel extends Model
{
    protected $table = 'notificacoes';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'empresa_id',
        'tipo',
        'canal',
        'nivel',
        'titulo',
        'mensagem',
        'referencia',
        'lida',
        'created_at'
    ];

    protected $useTimestamps = true;
}
