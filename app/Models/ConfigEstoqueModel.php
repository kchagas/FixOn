<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfigEstoqueModel extends Model
{
    // -------------------------------------------------------
    // Tabela que guarda parâmetros por empresa (multiempresa)
    // -------------------------------------------------------
    protected $table      = 'config_estoque';
    protected $primaryKey = 'id';

    // -------------------------------------------------------
    // Campos permitidos para insert/update
    // -------------------------------------------------------
    protected $allowedFields = [
        'empresa_id',
        'janela_consumo_dias',
        'cobertura_reposicao_dias',
        'dias_alerta_urgente',
        'dias_alerta_critico',
    ];

    // -------------------------------------------------------
    // Mantém consistência (você pode trocar para timestamps CI)
    // -------------------------------------------------------
    protected $returnType = 'array';

    /**
     * Retorna a config da empresa.
     * Se não existir, cria com defaults (ERP robusto: auto-healing).
     */
    public function getOrCreateByEmpresa(int $empresaId): array
    {
        // Busca config existente
        $config = $this->where('empresa_id', $empresaId)->first();

        // Se não existir, cria padrão e retorna
        if (!$config) {
            $this->insert([
                'empresa_id'              => $empresaId,
                'janela_consumo_dias'      => 30,
                'cobertura_reposicao_dias' => 15,
                'dias_alerta_urgente'      => 2,
                'dias_alerta_critico'      => 5,
            ]);

            $config = $this->where('empresa_id', $empresaId)->first();
        }

        return $config;
    }
}
