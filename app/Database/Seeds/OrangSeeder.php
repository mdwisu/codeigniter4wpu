<?php

namespace App\Database\Seeds;

use CodeIgniter\I18n\Time;

use CodeIgniter\Database\Seeder;

class OrangSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama' => 'Muhammad Dwi Susanto',
                'alamat'    => 'Jl. ABC No. 123',
                'created_at'    => Time::now(),
                'updated_at'    => Time::now(),
            ],
            [
                'nama' => 'anjing ngejar',
                'alamat'    => 'Jl. ABC No. 123',
                'created_at'    => Time::now(),
                'updated_at'    => Time::now(),
            ],
            [
                'nama' => 'babi ngejar',
                'alamat'    => 'Jl. ABC No. 123',
                'created_at'    => Time::now(),
                'updated_at'    => Time::now(),
            ],
        ];

        // Simple Queries
        // $this->db->query('INSERT INTO orang (nama, alamat, created_at, updated_at) VALUES(:nama:, :alamat:, :created_at:, :updated_at:)', $data);

        // Using Query Builder
        $this->db->table('orang')->insertBatch($data);
    }
}
