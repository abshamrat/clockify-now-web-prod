<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateUserActivitiesTable extends Migration
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
            'organization_id' => [
                'type' => 'INT',
                'constraint'     => 5,
                'unsigned' => true,
            ],
            'manual_time_id' => [
                'type' => 'INT',
                'constraint'     => 5,
                'unsigned' => true,
            ],
            'mouse_click' => [
                'type' => 'INT',
                'constraint'     => 5,
                'unsigned' => true,
                'default' => 0,
            ],
            'mouse_scroll' => [
                'type' => 'INT',
                'constraint'     => 5,
                'unsigned' => true,
                'default' => 0,
            ],
            'keyboard_activities' => [
                'type' => 'INT',
                'constraint'     => 5,
                'unsigned' => true,
                'default' => 0,
            ],
            'activity_per_slot' => [ // count of active minutes per slot, like 8 active minutes in 10 mins
                'type' => 'INT',
                'constraint'     => 5,
                'unsigned' => true,
            ],
            'activity_slot' => [ // 5/10/15 mins duration calculations
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
            'memo' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'screenshot_link' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'timestamp' => [
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
        $this->forge->addForeignKey('manual_time_id', 'user_manual_times', 'id');
        $this->forge->addForeignKey('organization_id', 'organizations', 'id');
        $this->forge->createTable('user_activities');
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('user_activities');
    }
}
