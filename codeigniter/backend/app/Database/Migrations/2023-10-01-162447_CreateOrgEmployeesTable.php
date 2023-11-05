<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrgEmployeesTable extends Migration
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
            'organization_id' => [
                'type' => 'INT',
                'constraint'     => 5,
                'unsigned' => true,
            ],
            'employee_id' => [
                'type' => 'INT',
                'constraint'     => 5,
                'unsigned' => true,
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('employee_id', 'employees', 'id');
        $this->forge->addForeignKey('organization_id', 'organizations', 'id');
        $this->forge->createTable('org_employees');
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('org_employees');
    }
}
