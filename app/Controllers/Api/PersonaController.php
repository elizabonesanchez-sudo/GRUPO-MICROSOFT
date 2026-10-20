<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\PersonaModel;
use App\Models\SexoModel;
use App\Models\EstadoCivilModel;
use App\Models\CorreoElectronicoModel;
use App\Models\DireccionModel;

class PersonaController extends ResourceController
{
    protected $format = 'json';

    protected PersonaModel $personaModel;
    protected SexoModel $sexoModel;
    protected EstadoCivilModel $estadoCivilModel;
    protected CorreoElectronicoModel $correoModel;
    protected DireccionModel $direccionModel;

    public function __construct()
    {
        $this->personaModel     = new PersonaModel();
        $this->sexoModel        = new SexoModel();
        $this->estadoCivilModel = new EstadoCivilModel();
        $this->correoModel      = new CorreoElectronicoModel();
        $this->direccionModel   = new DireccionModel();

        // Support CORS for all clients (Flutter Web, Mobile, Desktop)
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    }

    /**
     * Handle CORS preflight requests
     */
    public function options()
    {
        return $this->response->setStatusCode(200);
    }

    /**
     * GET /api/catalogos
     * Catálogos para formularios y selects
     */
    public function catalogos()
    {
        $sexos = $this->sexoModel->findAll();
        $estadosCiviles = $this->estadoCivilModel->findAll();

        return $this->respond([
            'status'  => 200,
            'message' => 'Catálogos obtenidos correctamente',
            'data'    => [
                'sexos'          => $sexos,
                'estadosCiviles' => $estadosCiviles,
            ],
        ]);
    }

    /**
     * GET /api/persona
     * List all personas with relations and optional search filter
     */
    public function index()
    {
        $search = $this->request->getGet('q') ?? $this->request->getGet('search');
        $personas = $this->personaModel->getPersonasOrdenadas($search);

        return $this->respond([
            'status'  => 200,
            'message' => 'Personas obtenidas correctamente',
            'data'    => $personas,
            'total'   => count($personas),
        ]);
    }

    /**
     * GET /api/persona/{id}
     * Get single persona with relations
     */
    public function show($id = null)
    {
        if (empty($id)) {
            return $this->failNotFound('ID de persona no proporcionado');
        }

        $persona = $this->personaModel->getPersonaDetail($id);
        if (!$persona) {
            return $this->failNotFound("Persona no encontrada con ID: {$id}");
        }

        $correos = $this->correoModel->getCorreosByPersona($id);
        // Map idcorreo for compatibility
        $mappedCorreos = array_map(function ($c) {
            if (!isset($c['idcorreo']) && isset($c['idcorreoelectronico'])) {
                $c['idcorreo'] = $c['idcorreoelectronico'];
            }
            return $c;
        }, $correos);

        $direcciones = $this->direccionModel->getDireccionesByPersona($id);

        $estadosCiviles = [];
        if (!empty($persona['idestadocivil']) && !empty($persona['estadocivil_nombre'])) {
            $estadosCiviles[] = [
                'idestadocivil'        => $persona['idestadocivil'],
                'estadocivil_nombre'   => $persona['estadocivil_nombre'],
            ];
        }

        $generos = [];
        if (!empty($persona['idsexo']) && !empty($persona['sexo_nombre'])) {
            $generos[] = [
                'idsexo'        => $persona['idsexo'],
                'genero_nombre' => $persona['sexo_nombre'],
            ];
        }

        $data = [
            'persona'        => $persona,
            'correos'        => $mappedCorreos,
            'direcciones'    => $direcciones,
            'estadosCiviles' => $estadosCiviles,
            'generos'        => $generos,
        ];

        return $this->respond([
            'status'  => 200,
            'message' => 'Persona obtenida correctamente',
            'data'    => $data,
        ]);
    }

    /**
     * POST /api/persona
     * Create a new persona
     */
    public function create()
    {
        $input = $this->request->getJSON(true) ?: $this->request->getPost();

        $rules = [
            'cedula'          => 'required|min_length[5]|max_length[20]|is_unique[persona.cedula]',
            'apellidos'       => 'required|min_length[2]|max_length[100]',
            'nombres'         => 'required|min_length[2]|max_length[100]',
            'fechanacimiento' => 'permit_empty|valid_date',
            'idsexo'          => 'permit_empty|is_natural_no_zero',
            'idestadocivil'   => 'permit_empty|is_natural_no_zero',
        ];

        if (!$this->validateData($input, $rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $personaData = [
            'cedula'          => trim($input['cedula']),
            'apellidos'       => trim($input['apellidos']),
            'nombres'         => trim($input['nombres']),
            'fechanacimiento' => !empty($input['fechanacimiento']) ? $input['fechanacimiento'] : null,
            'idsexo'          => !empty($input['idsexo']) ? (int)$input['idsexo'] : null,
            'idestadocivil'   => !empty($input['idestadocivil']) ? (int)$input['idestadocivil'] : null,
        ];

        $idpersona = $this->personaModel->insert($personaData);

        // Correo inicial opcional
        if (!empty($input['correo'])) {
            $correoText = trim($input['correo']);
            $fecha = date('Y-m-d');
            $idCorreoElectro = $this->correoModel->insert([
                'idpersona'      => $idpersona,
                'correo'         => $correoText,
                'nombre'         => $correoText,
                'tipo'           => 'Personal',
                'fechaoptencion' => $fecha,
            ]);

            // Sincronizar tabla 'correo' si existe
            if ($db->tableExists('correo')) {
                $db->table('correo')->insert([
                    'idcorreo'            => $idCorreoElectro ?: 0,
                    'idcorreoelectronico' => $idCorreoElectro ?: 0,
                    'idpersona'           => $idpersona,
                    'correo'              => $correoText,
                    'nombre'              => $correoText,
                    'tipo'                => 'Personal',
                    'fechaoptencion'      => $fecha,
                ]);
            }
        }

        // Dirección inicial opcional
        if (!empty($input['direccion'])) {
            $this->direccionModel->insert([
                'idpersona' => $idpersona,
                'direccion' => trim($input['direccion']),
                'nombre'    => trim($input['direccion']),
                'tipo'      => 'Domicilio',
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->failServerError('Error en la transacción al crear la persona');
        }

        $personaCreada = $this->personaModel->getPersonaDetail($idpersona);

        return $this->respondCreated([
            'status'  => 201,
            'message' => 'Persona creada exitosamente',
            'data'    => $personaCreada,
        ]);
    }

    /**
     * PUT|POST /api/persona/{id}
     * Update existing persona
     */
    public function update($id = null)
    {
        if (empty($id)) {
            return $this->failNotFound('ID de persona no proporcionado');
        }

        $persona = $this->personaModel->find($id);
        if (!$persona) {
            return $this->failNotFound("Persona no encontrada con ID: {$id}");
        }

        $input = $this->request->getJSON(true) ?: $this->request->getRawInput();
        if (empty($input)) {
            $input = $this->request->getPost();
        }

        $rules = [
            'cedula'          => "required|min_length[5]|max_length[20]|is_unique[persona.cedula,idpersona,{$id}]",
            'apellidos'       => 'required|min_length[2]|max_length[100]',
            'nombres'         => 'required|min_length[2]|max_length[100]',
            'fechanacimiento' => 'permit_empty|valid_date',
            'idsexo'          => 'permit_empty|is_natural_no_zero',
            'idestadocivil'   => 'permit_empty|is_natural_no_zero',
        ];

        if (!$this->validateData($input, $rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $updateData = [
            'cedula'    => trim($input['cedula']),
            'apellidos' => trim($input['apellidos']),
            'nombres'   => trim($input['nombres']),
        ];

        if (array_key_exists('fechanacimiento', $input)) {
            $updateData['fechanacimiento'] = !empty($input['fechanacimiento']) ? $input['fechanacimiento'] : null;
        }
        if (array_key_exists('idsexo', $input)) {
            $updateData['idsexo'] = !empty($input['idsexo']) ? (int)$input['idsexo'] : null;
        }
        if (array_key_exists('idestadocivil', $input)) {
            $updateData['idestadocivil'] = !empty($input['idestadocivil']) ? (int)$input['idestadocivil'] : null;
        }

        $this->personaModel->update($id, $updateData);
        $personaActualizada = $this->personaModel->getPersonaDetail($id);

        return $this->respond([
            'status'  => 200,
            'message' => 'Persona actualizada exitosamente',
            'data'    => $personaActualizada,
        ]);
    }

    /**
     * DELETE /api/persona/{id}
     * Delete persona and related records
     */
    public function delete($id = null)
    {
        if (empty($id)) {
            return $this->failNotFound('ID de persona no proporcionado');
        }

        $persona = $this->personaModel->find($id);
        if (!$persona) {
            return $this->failNotFound("Persona no encontrada con ID: {$id}");
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->correoModel->where('idpersona', $id)->delete();
        if ($db->tableExists('correo')) {
            $db->table('correo')->where('idpersona', $id)->delete();
        }
        $this->direccionModel->where('idpersona', $id)->delete();
        $this->personaModel->delete($id);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->failServerError('Error al eliminar la persona');
        }

        return $this->respondDeleted([
            'status'  => 200,
            'message' => 'Persona eliminada exitosamente',
            'data'    => ['idpersona' => $id],
        ]);
    }
}
