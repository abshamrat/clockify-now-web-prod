<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateEmployeeLeavesTable extends Migration
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
            'leave_type' => [
                'type'      => 'ENUM("absent","casual_leave","sick_leave")',
                'default'   => 'absent',
                'null'      => false,
            ],
            'status' => [
                'type'      => 'ENUM("pending","rejected", "approved")',
                'default'   => 'pending',
                'null'      => false,
            ],
            'from_date' => [
                'type'      => 'DATE',
                'null'      => false,
            ],
            'to_date' => [
                'type'      => 'DATE',
                'null'      => false,
            ],
            'number_of_days' => [
                'type'          => 'INT',
                'constraint'    => 5,
                'unsigned'      => true,
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
                'on update' => 'NOW()'
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id');
        $this->forge->createTable('employee_leaves');
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('employee_leaves');
    }
}
