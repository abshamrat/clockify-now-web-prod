<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRolePermissionsTable extends Migration
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
            'route_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null' => false
            ],
            'route' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null' => false
            ],
            'role_id' => [
                'type' => 'INT',
                'constraint'     => 5,
                'unsigned' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('role_id', 'roles', 'id');
        $this->forge->createTable('role_permissions');
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('role_permissions');
    }
}
