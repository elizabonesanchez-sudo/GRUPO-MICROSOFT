<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\SexoModel;

class SexoApiController extends ResourceController
{
    protected $format = 'json';
    protected SexoModel $sexoModel;

    public function __construct()
    {
        $this->sexoModel = new SexoModel();
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
        $data = $this->sexoModel->findAll();
        return $this->respond([
            'status'  => 200,
            'message' => 'Sexos obtenidos correctamente',
            'data'    => $data,
            'total'   => count($data),
        ]);
    }

    public function show($id = null)
    {
        if (empty($id)) {
            return $this->failNotFound('ID no proporcionado');
        }
        $item = $this->sexoModel->find($id);
        if (!$item) {
            return $this->failNotFound('Sexo no encontrado');
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
        $id = $this->sexoModel->insert(['nombre' => trim($input['nombre'])]);
        if (!$id) {
            return $this->failServerError('Error al registrar sexo');
        }
        return $this->respondCreated([
            'status'  => 201,
            'message' => 'Sexo creado exitosamente',
            'data'    => $this->sexoModel->find($id),
        ]);
    }

    public function update($id = null)
    {
        if (empty($id)) {
            return $this->failNotFound('ID no proporcionado');
        }
        $item = $this->sexoModel->find($id);
        if (!$item) {
            return $this->failNotFound('Sexo no encontrado');
        }
        $input = $this->request->getJSON(true) ?: $this->request->getRawInput();
        if (empty($input)) {
            $input = $this->request->getPost();
        }
        if (empty($input['nombre'])) {
            return $this->failValidationErrors(['nombre' => 'El nombre es obligatorio']);
        }
        $this->sexoModel->update($id, ['nombre' => trim($input['nombre'])]);
        return $this->respond([
            'status'  => 200,
            'message' => 'Sexo actualizado exitosamente',
            'data'    => $this->sexoModel->find($id),
        ]);
    }

    public function delete($id = null)
    {
        if (empty($id)) {
            return $this->failNotFound('ID no proporcionado');
        }
        $item = $this->sexoModel->find($id);
        if (!$item) {
            return $this->failNotFound('Sexo no encontrado');
        }
        $db = \Config\Database::connect();
        $inUse = $db->table('persona')->where('idsexo', $id)->countAllResults();
        if ($inUse > 0) {
            return $this->failResourceExists("No se puede eliminar porque está asignado a {$inUse} persona(s)");
        }
        $this->sexoModel->delete($id);
        return $this->respondDeleted([
            'status'  => 200,
            'message' => 'Sexo eliminado exitosamente',
            'data'    => ['idsexo' => $id],
        ]);
    }
}
