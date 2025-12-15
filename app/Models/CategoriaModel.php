<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriaModel extends Model
{
    // Nome da tabela no banco
    protected $table = 'categorias';

    // Chave primária
    protected $primaryKey = 'id';

    // Campos permitidos para INSERT / UPDATE
    protected $allowedFields = [
        'nome',
        'descricao',
        'empresa_id',
        'ativo'
    ];

    // Habilita timestamps automáticos (created_at / updated_at)
    protected $useTimestamps = true;

    /**
     * Regras de validação
     * - Nome obrigatório
     * - Evita categoria vazia ou curta
     */
    protected $validationRules = [
        'nome' => 'required|min_length[3]'
    ];

    protected $validationMessages = [
        'nome' => [
            'required'   => 'O nome da categoria é obrigatório.',
            'min_length' => 'O nome deve ter no mínimo 3 caracteres.'
        ]
    ];
}
