<?php

namespace App\Models;

use CodeIgniter\Model;

class PecaModel extends Model
{
    protected $table = 'pecas';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nome',
        'descricao',
        'sku',
        'unidade_medida',
        'estoque_minimo',
        'estoque_atual',
        'preco_custo',
        'preco_venda',
        'empresa_id'
    ];

    protected $returnType = 'array';

    // Regras de validação
   protected $validationRules = [
    'nome'            => 'required|min_length[3]|max_length[150]',
    'sku'             => 'required',
    'unidade_medida'  => 'required',
    'estoque_minimo'  => 'required|integer|greater_than_equal_to[1]',
    'preco_custo'     => 'decimal',
    'preco_venda'     => 'decimal'
];
    protected $validationMessages = [
        'sku' => [
            'is_unique' => 'Este SKU já existe no sistema.'
        ]
    ];
}
