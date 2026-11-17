<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAdmsTables extends Migration
{
    public function up()
    {
        // adms_devices
        $this->forge->addField([
            'sn' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'last_stamp' => [
                'type'       => 'INT',
                'default'    => 0,
            ],
            'last_opstamp' => [
                'type'       => 'INT',
                'default'    => 0,
            ],
            'last_activity' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'fw_version' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'online' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
        ]);
        $this->forge->addKey('sn', true);
        $this->forge->createTable('adms_devices', true);

        // adms_users
        $this->forge->addField([
            'pin' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'privilege' => [
                'type'       => 'INT',
                'default'    => 0,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'card' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'group_id' => [
                'type'       => 'INT',
                'null'       => true,
            ],
            'last_sync_sn' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('pin', true);
        $this->forge->createTable('adms_users', true);

        // adms_attlog
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'sn' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'pin' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'scan_date' => [
                'type'       => 'DATETIME',
            ],
            'status' => [
                'type'       => 'INT',
                'null'       => true,
            ],
            'verify' => [
                'type'       => 'INT',
                'null'       => true,
            ],
            'raw' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('sn');
        $this->forge->addKey('pin');
        $this->forge->addKey('scan_date');
        $this->forge->createTable('adms_attlog', true);

        // adms_templates
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'pin' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'fid' => [
                'type'       => 'INT',
            ],
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['fp', 'face'],
            ],
            'tmp' => [
                'type'       => 'LONGTEXT',
            ],
            'tmp_size' => [
                'type'       => 'INT',
            ],
            'valid' => [
                'type'       => 'INT',
                'default'    => 1,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('pin');
        $this->forge->createTable('adms_templates', true);

        // adms_commands
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'sn' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true, // NULL = broadcast to all
            ],
            'command' => [
                'type'       => 'TEXT',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'sent', 'done', 'error'],
                'default'    => 'pending',
            ],
            'response' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'updated_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('sn');
        $this->forge->createTable('adms_commands', true);
    }

    public function down()
    {
        $this->forge->dropTable('adms_commands', true);
        $this->forge->dropTable('adms_templates', true);
        $this->forge->dropTable('adms_attlog', true);
        $this->forge->dropTable('adms_users', true);
        $this->forge->dropTable('adms_devices', true);
    }
}
