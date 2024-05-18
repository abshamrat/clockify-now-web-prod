<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDesignationsTable extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null' => false
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null' => false
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('designations');
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('designations');
    }
}
