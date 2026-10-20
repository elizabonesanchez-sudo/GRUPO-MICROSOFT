<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PersonaSeeder extends Seeder
{
    public function run()
    {
        // 1. Datos para Sexo
        $sexos = [
            ['idsexo' => 1, 'nombre' => 'Masculino'],
            ['idsexo' => 2, 'nombre' => 'Femenino'],
            ['idsexo' => 3, 'nombre' => 'No Binario'],
            ['idsexo' => 4, 'nombre' => 'Prefiero no especificar'],
        ];
        $this->db->table('sexo')->insertBatch($sexos);

        // 2. Datos para Estado Civil
        $estadosCiviles = [
            ['idestadocivil' => 1, 'nombre' => 'Soltero/a'],
            ['idestadocivil' => 2, 'nombre' => 'Casado/a'],
            ['idestadocivil' => 3, 'nombre' => 'Divorciado/a'],
            ['idestadocivil' => 4, 'nombre' => 'Viudo/a'],
            ['idestadocivil' => 5, 'nombre' => 'Unión Libre / De Hecho'],
        ];
        $this->db->table('estadocivil')->insertBatch($estadosCiviles);

        // 3. Datos para Persona
        $personas = [
            [
                'idpersona'       => 1,
                'cedula'          => '0801234567',
                'nombres'         => 'Carlos Alberto',
                'apellidos'       => 'Mendoza Zambrano',
                'fechanacimiento' => '1992-05-14',
                'idsexo'          => 1,
                'idestadocivil'   => 1,
            ],
            [
                'idpersona'       => 2,
                'cedula'          => '0802345678',
                'nombres'         => 'María Fernanda',
                'apellidos'       => 'Quiñónez Hurtado',
                'fechanacimiento' => '1995-11-23',
                'idsexo'          => 2,
                'idestadocivil'   => 2,
            ],
            [
                'idpersona'       => 3,
                'cedula'          => '0803456789',
                'nombres'         => 'Luis Eduardo',
                'apellidos'       => 'Valencia Caicedo',
                'fechanacimiento' => '1988-03-30',
                'idsexo'          => 1,
                'idestadocivil'   => 5,
            ],
            [
                'idpersona'       => 4,
                'cedula'          => '0804567890',
                'nombres'         => 'Ana Patricia',
                'apellidos'       => 'Estupiñán Cortez',
                'fechanacimiento' => '1999-08-19',
                'idsexo'          => 2,
                'idestadocivil'   => 1,
            ],
            [
                'idpersona'       => 5,
                'cedula'          => '0805678901',
                'nombres'         => 'Alex Gabriel',
                'apellidos'       => 'Montaño Bone',
                'fechanacimiento' => '2001-01-10',
                'idsexo'          => 3,
                'idestadocivil'   => 1,
            ],
            [
                'idpersona'       => 6,
                'cedula'          => '0806789012',
                'nombres'         => 'Rosa Elena',
                'apellidos'       => 'Arroyo Mina',
                'fechanacimiento' => '1985-07-04',
                'idsexo'          => 2,
                'idestadocivil'   => 3,
            ],
        ];
        $this->db->table('persona')->insertBatch($personas);

        // 4. Datos para Direccion
        $direcciones = [
            [
                'idpersona' => 1,
                'direccion' => 'Av. Olmedo y Rocafuerte, Barrio Central',
                'nombre'    => 'Av. Olmedo y Rocafuerte, Barrio Central',
                'tipo'      => 'Domicilio',
            ],
            [
                'idpersona' => 1,
                'direccion' => 'Ciudadela Tolita 1, Manzana B Villa 12',
                'nombre'    => 'Ciudadela Tolita 1, Manzana B Villa 12',
                'tipo'      => 'Trabajo',
            ],
            [
                'idpersona' => 2,
                'direccion' => 'Calle Bolívar 405 y 10 de Agosto',
                'nombre'    => 'Calle Bolívar 405 y 10 de Agosto',
                'tipo'      => 'Domicilio',
            ],
            [
                'idpersona' => 3,
                'direccion' => 'Av. Kennedy frente al Parque Infantil',
                'nombre'    => 'Av. Kennedy frente al Parque Infantil',
                'tipo'      => 'Domicilio',
            ],
            [
                'idpersona' => 4,
                'direccion' => 'Sector Las Palmas, Malecón y Calle 6',
                'nombre'    => 'Sector Las Palmas, Malecón y Calle 6',
                'tipo'      => 'Domicilio',
            ],
            [
                'idpersona' => 5,
                'direccion' => 'Av. Pedro Vicente Maldonado y Sucre',
                'nombre'    => 'Av. Pedro Vicente Maldonado y Sucre',
                'tipo'      => 'Domicilio',
            ],
            [
                'idpersona' => 6,
                'direccion' => 'Barrio Nueva Esperanza, Calle Principal #45',
                'nombre'    => 'Barrio Nueva Esperanza, Calle Principal #45',
                'tipo'      => 'Domicilio',
            ],
        ];
        $this->db->table('direccion')->insertBatch($direcciones);

        // 5. Datos para Correo Electrónico
        $correos = [
            [
                'idpersona'      => 1,
                'correo'         => 'carlos.mendoza@gmail.com',
                'nombre'         => 'carlos.mendoza@gmail.com',
                'tipo'           => 'Personal',
                'fechaoptencion' => '2023-01-15',
            ],
            [
                'idpersona'      => 1,
                'correo'         => 'cmendoza@empresa.ec',
                'nombre'         => 'cmendoza@empresa.ec',
                'tipo'           => 'Institucional',
                'fechaoptencion' => '2023-06-20',
            ],
            [
                'idpersona'      => 2,
                'correo'         => 'maria.fernanda.q@outlook.com',
                'nombre'         => 'maria.fernanda.q@outlook.com',
                'tipo'           => 'Personal',
                'fechaoptencion' => '2022-10-10',
            ],
            [
                'idpersona'      => 3,
                'correo'         => 'luis.valencia.c@hotmail.com',
                'nombre'         => 'luis.valencia.c@hotmail.com',
                'tipo'           => 'Personal',
                'fechaoptencion' => '2021-04-12',
            ],
            [
                'idpersona'      => 4,
                'correo'         => 'ana.estupinan@utlvte.edu.ec',
                'nombre'         => 'ana.estupinan@utlvte.edu.ec',
                'tipo'           => 'Institucional',
                'fechaoptencion' => '2024-02-01',
            ],
            [
                'idpersona'      => 5,
                'correo'         => 'alex.montano@yahoo.com',
                'nombre'         => 'alex.montano@yahoo.com',
                'tipo'           => 'Personal',
                'fechaoptencion' => '2024-05-18',
            ],
            [
                'idpersona'      => 6,
                'correo'         => 'rosa.arroyo@gmail.com',
                'nombre'         => 'rosa.arroyo@gmail.com',
                'tipo'           => 'Personal',
                'fechaoptencion' => '2020-09-05',
            ],
        ];
        $this->db->table('correoelectronico')->insertBatch($correos);
    }
}
