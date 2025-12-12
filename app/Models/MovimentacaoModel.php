<?php

namespace App\Models;

use CodeIgniter\Model;

class MovimentacaoModel extends Model
{
    protected $table      = 'movimentacoes_estoque';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'peca_id',
        'tipo',
        'quantidade',
        'motivo',
        'usuario_id'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
}
