<?php

namespace App\Models;

use CodeIgniter\Model;

class AlertaEstoqueModel extends Model
{
    // -------------------------------------------------------
    // Log interno dos alertas gerados
    // -------------------------------------------------------
    protected $table      = 'alertas_estoque';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'empresa_id',
        'peca_id',
        'nivel',
        'estoque_atual',
        'estoque_minimo',
        'consumo_medio_dia',
        'dias_para_zerar',
        'qtd_sugerida',
        'mensagem',
        'resolvido',
        'resolvido_em',
    ];

    protected $useTimestamps = true; // usa created_at / updated_at
    protected $returnType    = 'array';
}
