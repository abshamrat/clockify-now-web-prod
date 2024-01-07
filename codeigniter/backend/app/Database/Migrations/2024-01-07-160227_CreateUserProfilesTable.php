<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateUserProfilesTable extends Migration
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
            'user_id' => [
                'type' => 'INT',
                'constraint'     => 5,
                'unsigned' => true,
            ],
            'first_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'last_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'designation_id' => [
                'type' => 'INT',
                'constraint'     => 5,
                'unsigned' => true,
            ],
            'city' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'state_id' => [
                'type' => 'INT',
                'constraint'     => 5,
                'unsigned' => true,
            ],
            'zip_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'country_id' => [
                'type' => 'INT',
                'constraint'     => 5,
                'unsigned' => true,
            ],
            'profile_image_link' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id');
        $this->forge->addForeignKey('designation_id', 'designations', 'id');
        // $this->forge->addForeignKey('state_id', 'states', 'id');
        // $this->forge->addForeignKey('country_id', 'countries', 'id');
        $this->forge->createTable('user_profiles');
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('user_profiles');
    }
}
