<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePersonaTables extends Migration
{
    public function up()
    {
        // 1. Tabla: sexo
        $this->forge->addField([
            'idsexo' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
            ],
        ]);
        $this->forge->addKey('idsexo', true);
        $this->forge->createTable('sexo', true);

        // 2. Tabla: estadocivil
        $this->forge->addField([
            'idestadocivil' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
            ],
        ]);
        $this->forge->addKey('idestadocivil', true);
        $this->forge->createTable('estadocivil', true);

        // 3. Tabla: persona
        $this->forge->addField([
            'idpersona' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'cedula' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
            ],
            'nombres' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'apellidos' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'fechanacimiento' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'idsexo' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'idestadocivil' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('idpersona', true);
        $this->forge->addUniqueKey('cedula');
        $this->forge->addKey('idsexo');
        $this->forge->addKey('idestadocivil');
        $this->forge->addForeignKey('idsexo', 'sexo', 'idsexo', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('idestadocivil', 'estadocivil', 'idestadocivil', 'CASCADE', 'SET NULL');
        $this->forge->createTable('persona', true);

        // 4. Tabla: direccion
        $this->forge->addField([
            'iddireccion' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'idpersona' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            'direccion' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'tipo' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'Domicilio',
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('iddireccion', true);
        $this->forge->addKey('idpersona');
        $this->forge->addForeignKey('idpersona', 'persona', 'idpersona', 'CASCADE', 'CASCADE');
        $this->forge->createTable('direccion', true);

        // 5. Tabla: correoelectronico
        $this->forge->addField([
            'idcorreoelectronico' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'idpersona' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            'correo' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => false,
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'tipo' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'Personal',
                'null'       => true,
            ],
            'fechaoptencion' => [
                'type' => 'DATE',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('idcorreoelectronico', true);
        $this->forge->addKey('idpersona');
        $this->forge->addForeignKey('idpersona', 'persona', 'idpersona', 'CASCADE', 'CASCADE');
        $this->forge->createTable('correoelectronico', true);
        
        // 6. Crear tabla clientes
$this->forge->addField([
    'idcliente' => [
        'type'           => 'INT',
        'constraint'     => 11,
        'unsigned'       => true,
        'auto_increment' => true,
    ],
    'nombre' => [
        'type'       => 'VARCHAR',
        'constraint' => 100,
        'null'       => false,
    ],
    'correo' => [
        'type'       => 'VARCHAR',
        'constraint' => 100,
        'null'       => true,
    ],
    'created_at' => [
        'type' => 'DATETIME',
        'null' => true,
    ],
]);

$this->forge->addKey('idcliente', true);
$this->forge->createTable('clientes');

        // Vista de compatibilidad para consultas directas sobre 'correo'
        $this->db->query("CREATE OR REPLACE VIEW correo AS 
            SELECT idcorreoelectronico AS idcorreo, idcorreoelectronico, idpersona, correo, nombre, tipo, fechaoptencion 
            FROM correoelectronico");
    }

    public function down()
    {
        $this->db->query("DROP VIEW IF EXISTS correo");
        $this->forge->dropTable('correoelectronico', true);
        $this->forge->dropTable('direccion', true);
        $this->forge->dropTable('persona', true);
        $this->forge->dropTable('estadocivil', true);
        $this->forge->dropTable('sexo', true);
    }
}
