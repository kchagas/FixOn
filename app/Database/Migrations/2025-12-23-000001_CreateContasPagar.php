<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateContasPagar extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            // Multiempresa (SaaS)
            'empresa_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            // Descrição da despesa
            'descricao' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],

            // Valor da conta
            'valor' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],

            // Datas
            'data_vencimento' => [
                'type' => 'DATE',
            ],

            'data_pagamento' => [
                'type' => 'DATE',
                'null' => true,
            ],

            // Status financeiro
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['aberto', 'pago', 'atrasado'],
                'default'    => 'aberto',
            ],

            // Origem da conta (preparado para o futuro)
            'origem' => [
                'type'       => 'ENUM',
                'constraint' => ['manual', 'estoque', 'compra'],
                'default'    => 'manual',
            ],

            // Observações livres
            'observacao' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            // Auditoria
            'created_at' => [
                'type' => 'DATETIME',
            ],

            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('contas_pagar');
    }

    public function down()
    {
        $this->forge->dropTable('contas_pagar');
    }
}
