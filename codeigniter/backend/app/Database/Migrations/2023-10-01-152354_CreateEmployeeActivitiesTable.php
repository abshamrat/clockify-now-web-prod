<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateEmployeeActivitiesTable extends Migration
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
            'mouse_activities' => [
                'type' => 'INT',
                'constraint'     => 5,
                'unsigned' => true,
            ],
            'keyboard_activities' => [
                'type' => 'INT',
                'constraint'     => 5,
                'unsigned' => true,
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
            'screenshot_path' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'employee_id' => [
                'type' => 'INT',
                'constraint'     => 5,
                'unsigned' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('employee_id', 'employees', 'id');
        $this->forge->createTable('employee_activities');
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('employee_activities');
    }
}
