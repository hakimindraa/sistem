<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMahasiswaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'nim' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'angkatan' => [
                'type'       => 'INT',
                'constraint' => 4,
            ],
            'jurusan' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
        ]);
        
        $this->forge->addKey('nim', true);
        $this->forge->createTable('mahasiswa');
    }

    public function down()
    {
        $this->forge->dropTable('mahasiswa');
    }
}
