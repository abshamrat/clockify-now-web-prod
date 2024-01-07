<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DesignationSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'code' => 'CEO',
                'name'    => 'CEO',
            ],
            [
                'code' => 'CTO',
                'name'    => 'CTO',
            ],
            [
                'code' => 'EM',
                'name'    => 'Engineering Manager',
            ],
            [
                'code' => 'SE',
                'name'    => 'Software Engineer',
            ],
            [
                'code' => 'UXD',
                'name'    => 'UX Designer',
            ],
        ];

        foreach($data as $d) {
            // $this->db->query('INSERT INTO designations (code, `name`) VALUES('.$d["code"].', )', );
            $this->db->table('designations')->insert($d);

        }
    }
}
