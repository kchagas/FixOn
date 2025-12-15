<?php

namespace App\Models;

use CodeIgniter\Model;

class PecaModel extends Model
{
    protected $table      = 'pecas';
    protected $primaryKey = 'id';

    /**
     * CAMPOS PERMITIDOS
     * ⚠️ Se não estiver aqui, o CodeIgniter IGNORA
     */
    protected $allowedFields = [
        'nome',
        'descricao',
        'categoria_id',   // ✅ ESSENCIAL
        'sku',
        'unidade_medida',
        'estoque_minimo',
        'estoque_atual',
        'preco_custo',
        'preco_venda',
        'empresa_id'
    ];

    protected $returnType = 'array';

    /**
     * REGRAS DE VALIDAÇÃO
     */
    protected $validationRules = [
        'nome'           => 'required|min_length[3]|max_length[150]',
        'categoria_id'   => 'required|integer', // ✅ Categoria obrigatória
        'sku'            => 'required',
        'unidade_medida' => 'required',
        'estoque_minimo' => 'required|integer|greater_than_equal_to[1]',
        'preco_custo'    => 'decimal',
        'preco_venda'    => 'decimal'
    ];

    protected $validationMessages = [
        'categoria_id' => [
            'required' => 'A categoria é obrigatória.',
            'integer'  => 'Categoria inválida.'
        ],
        'sku' => [
            'required' => 'O SKU é obrigatório.'
        ]
    ];
}
