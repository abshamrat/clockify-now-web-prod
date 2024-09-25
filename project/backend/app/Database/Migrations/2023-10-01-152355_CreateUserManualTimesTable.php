<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateUserManualTimesTable extends Migration
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
            'activity_id' => [
                'type' => 'INT',
                'constraint'     => 5,
                'unsigned' => true,
                'default' => 0,
            ],
            'date' => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'timezone' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
            ],
            'start_time' => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'end_time' => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'memo' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'attachment_link' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'is_approved' => [
                'type' => 'INT',
                'constraint'     => 5,
                'unsigned' => true,
                'default' => 0,
            ],
            'approved_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id');
        $this->forge->addForeignKey('activity_id', 'activity_types', 'id');
        $this->forge->createTable('user_manual_times');
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('user_manual_times');
    }
}
