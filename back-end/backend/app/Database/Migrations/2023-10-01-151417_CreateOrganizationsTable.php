<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrganizationsTable extends Migration
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
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'domain' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'phone_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '15',
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
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('country_id', 'countries', 'id');
        $this->forge->addForeignKey('state_id', 'states', 'id');
        $this->forge->createTable('organizations');
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('organizations');
    }
}
