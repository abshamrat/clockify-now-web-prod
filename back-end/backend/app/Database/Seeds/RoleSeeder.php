<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'role_name'    => 'Employee',
        ];

        // Simple Queries
        // $this->db->query('INSERT INTO roles (username, email) VALUES(:username:, :email:)', $data);

        // Using Query Builder
        $this->db->table('roles')->insert($data);
    }
}
