<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmployeesTable extends Migration
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
            'first_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'last_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'country_id' => [
                'type' => 'INT',
                'constraint'     => 5,
                'unsigned' => true,
            ],
            'state_id' => [
                'type' => 'INT',
                'constraint'     => 5,
                'unsigned' => true,
            ],
            'city' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'zip_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
            ],
            'address' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'employee_title_id' => [
                'type' => 'INT',
                'constraint'     => 5,
                'unsigned' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('employee_title_id', 'employee_titles', 'id');
        $this->forge->createTable('employees');
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('employees');
    }
}
