<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\EstadoCivilModel;

class EstadoCivilApiController extends ResourceController
{
    protected $format = 'json';
    protected EstadoCivilModel $estadoCivilModel;

    public function __construct()
    {
        $this->estadoCivilModel = new EstadoCivilModel();
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    }

    public function options()
    {
        return $this->response->setStatusCode(200);
    }

    public function index()
    {
        $data = $this->estadoCivilModel->findAll();
        return $this->respond([
            'status'  => 200,
            'message' => 'Estados civiles obtenidos correctamente',
            'data'    => $data,
            'total'   => count($data),
        ]);
    }

    public function show($id = null)
    {
        if (empty($id)) {
            return $this->failNotFound('ID no proporcionado');
        }
        $item = $this->estadoCivilModel->find($id);
        if (!$item) {
            return $this->failNotFound('Estado civil no encontrado');
        }
        return $this->respond([
            'status' => 200,
            'data'   => $item,
        ]);
    }

    public function create()
    {
        $input = $this->request->getJSON(true) ?: $this->request->getPost();
        if (empty($input['nombre'])) {
            return $this->failValidationErrors(['nombre' => 'El nombre es obligatorio']);
        }
        $id = $this->estadoCivilModel->insert(['nombre' => trim($input['nombre'])]);
        if (!$id) {
            return $this->failServerError('Error al registrar estado civil');
        }
        return $this->respondCreated([
            'status'  => 201,
            'message' => 'Estado civil creado exitosamente',
            'data'    => $this->estadoCivilModel->find($id),
        ]);
    }

    public function update($id = null)
    {
        if (empty($id)) {
            return $this->failNotFound('ID no proporcionado');
        }
        $item = $this->estadoCivilModel->find($id);
        if (!$item) {
            return $this->failNotFound('Estado civil no encontrado');
        }
        $input = $this->request->getJSON(true) ?: $this->request->getRawInput();
        if (empty($input)) {
            $input = $this->request->getPost();
        }
        if (empty($input['nombre'])) {
            return $this->failValidationErrors(['nombre' => 'El nombre es obligatorio']);
        }
        $this->estadoCivilModel->update($id, ['nombre' => trim($input['nombre'])]);
        return $this->respond([
            'status'  => 200,
            'message' => 'Estado civil actualizado exitosamente',
            'data'    => $this->estadoCivilModel->find($id),
        ]);
    }

    public function delete($id = null)
    {
        if (empty($id)) {
            return $this->failNotFound('ID no proporcionado');
        }
        $item = $this->estadoCivilModel->find($id);
        if (!$item) {
            return $this->failNotFound('Estado civil no encontrado');
        }
        $db = \Config\Database::connect();
        $inUse = $db->table('persona')->where('idestadocivil', $id)->countAllResults();
        if ($inUse > 0) {
            return $this->failResourceExists("No se puede eliminar porque está asignado a {$inUse} persona(s)");
        }
        $this->estadoCivilModel->delete($id);
        return $this->respondDeleted([
            'status'  => 200,
            'message' => 'Estado civil eliminado exitosamente',
            'data'    => ['idestadocivil' => $id],
        ]);
    }
}
