<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotificacoes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type'=>'INT','auto_increment'=>true],
            'empresa_id' => ['type'=>'INT'],
            'tipo' => [
                'type'=>'ENUM',
                'constraint'=>['estoque'],
                'default'=>'estoque'
            ],
            'canal' => [
                'type'=>'ENUM',
                'constraint'=>['simulado'],
                'default'=>'simulado'
            ],
            'nivel' => [
                'type'=>'ENUM',
                'constraint'=>['urgente','critico','atencao'],
            ],
            'titulo' => ['type'=>'VARCHAR','constraint'=>150],
            'mensagem' => ['type'=>'TEXT'],
            'referencia' => ['type'=>'VARCHAR','constraint'=>100],
            'lida' => ['type'=>'BOOLEAN','default'=>0],
            'created_at' => ['type'=>'DATETIME','null'=>true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('notificacoes');
    }

    public function down()
    {
        $this->forge->dropTable('notificacoes');
    }
}
