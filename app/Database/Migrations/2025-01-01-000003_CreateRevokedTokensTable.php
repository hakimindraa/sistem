<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRevokedTokensTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'jti' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'unique'     => true,
            ],
            'expired_at' => [
                'type' => 'DATETIME',
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('jti');
        $this->forge->createTable('revoked_tokens');
    }

    public function down()
    {
        $this->forge->dropTable('revoked_tokens');
    }
}
