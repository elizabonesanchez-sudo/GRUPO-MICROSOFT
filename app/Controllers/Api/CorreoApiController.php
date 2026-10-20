<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\CorreoElectronicoModel;
use App\Models\PersonaModel;

class CorreoApiController extends ResourceController
{
    protected $format = 'json';
    protected CorreoElectronicoModel $correoModel;
    protected PersonaModel $personaModel;

    public function __construct()
    {
        $this->correoModel  = new CorreoElectronicoModel();
        $this->personaModel = new PersonaModel();
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    }

    public function options()
    {
        return $this->response->setStatusCode(200);
    }

    public function index($idpersonaParam = null)
    {
        $search = $this->request->getGet('q') ?? $this->request->getGet('search');
        $idpersona = $idpersonaParam ?? $this->request->getGet('idpersona');

        if (!empty($idpersona)) {
            $data = $this->correoModel->getCorreosWithPersona($search, (int)$idpersona);
        } else {
            $data = $this->correoModel->getCorreosWithPersona($search);
        }

        // Asegurar que idcorreo esté presente para compatibilidad
        $mapped = array_map(function ($item) {
            if (!isset($item['idcorreo']) && isset($item['idcorreoelectronico'])) {
                $item['idcorreo'] = $item['idcorreoelectronico'];
            }
            if (!isset($item['persona_nombres']) && isset($item['nombres'])) {
                $apellidos = $item['apellidos'] ?? '';
                $item['persona_nombres'] = trim("{$apellidos} {$item['nombres']}");
            }
            return $item;
        }, $data);

        return $this->respond([
            'status'  => 200,
            'message' => 'Correos obtenidos correctamente',
            'data'    => $mapped,
            'total'   => count($mapped),
        ]);
    }

    public function show($id = null)
    {
        if (empty($id)) {
            return $this->failNotFound('ID no proporcionado');
        }
        $item = $this->correoModel->getCorreoDetail($id);
        if (!$item) {
            return $this->failNotFound('Correo no encontrado');
        }
        if (!isset($item['idcorreo']) && isset($item['idcorreoelectronico'])) {
            $item['idcorreo'] = $item['idcorreoelectronico'];
        }
        return $this->respond([
            'status' => 200,
            'data'   => $item,
        ]);
    }

    public function create()
    {
        $input = $this->request->getJSON(true) ?: $this->request->getPost();
        $rules = [
            'idpersona' => 'required|is_natural_no_zero',
            'correo'    => 'required|valid_email|max_length[150]',
        ];
        if (!$this->validateData($input, $rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $idpersona = (int)$input['idpersona'];
        if (!$this->personaModel->find($idpersona)) {
            return $this->failNotFound("La persona con ID {$idpersona} no existe");
        }

        $correoText = trim($input['correo']);
        $fecha = !empty($input['fechaoptencion']) ? $input['fechaoptencion'] : date('Y-m-d');
        $tipo = !empty($input['tipo']) ? trim($input['tipo']) : 'Personal';

        $id = $this->correoModel->insert([
            'idpersona'      => $idpersona,
            'correo'         => $correoText,
            'nombre'         => $correoText,
            'tipo'           => $tipo,
            'fechaoptencion' => $fecha,
        ]);

        if (!$id) {
            return $this->failServerError('Error al registrar correo');
        }

        $db = \Config\Database::connect();
        if ($db->tableExists('correo')) {
            $db->table('correo')->insert([
                'idcorreo'            => $id,
                'idcorreoelectronico' => $id,
                'idpersona'           => $idpersona,
                'correo'              => $correoText,
                'nombre'              => $correoText,
                'tipo'                => $tipo,
                'fechaoptencion'      => $fecha,
            ]);
        }

        $creado = $this->correoModel->getCorreoDetail($id);
        if ($creado && !isset($creado['idcorreo'])) {
            $creado['idcorreo'] = $creado['idcorreoelectronico'];
        }

        return $this->respondCreated([
            'status'  => 201,
            'message' => 'Correo registrado exitosamente',
            'data'    => $creado,
        ]);
    }

    public function update($id = null)
    {
        if (empty($id)) {
            return $this->failNotFound('ID no proporcionado');
        }
        $item = $this->correoModel->find($id);
        if (!$item) {
            return $this->failNotFound('Correo no encontrado');
        }

        $input = $this->request->getJSON(true) ?: $this->request->getRawInput();
        if (empty($input)) {
            $input = $this->request->getPost();
        }

        $rules = [
            'correo' => 'required|valid_email|max_length[150]',
        ];
        if (!$this->validateData($input, $rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $correoText = trim($input['correo']);
        $updateData = ['correo' => $correoText, 'nombre' => $correoText];

        if (!empty($input['fechaoptencion'])) {
            $updateData['fechaoptencion'] = $input['fechaoptencion'];
        }
        if (!empty($input['tipo'])) {
            $updateData['tipo'] = trim($input['tipo']);
        }
        if (!empty($input['idpersona'])) {
            $updateData['idpersona'] = (int)$input['idpersona'];
        }

        $this->correoModel->update($id, $updateData);

        $db = \Config\Database::connect();
        if ($db->tableExists('correo')) {
            $db->table('correo')
               ->where('idcorreoelectronico', $id)
               ->orWhere('idcorreo', $id)
               ->update($updateData);
        }

        $actualizado = $this->correoModel->getCorreoDetail($id);
        if ($actualizado && !isset($actualizado['idcorreo'])) {
            $actualizado['idcorreo'] = $actualizado['idcorreoelectronico'];
        }

        return $this->respond([
            'status'  => 200,
            'message' => 'Correo actualizado exitosamente',
            'data'    => $actualizado,
        ]);
    }

    public function delete($id = null)
    {
        if (empty($id)) {
            return $this->failNotFound('ID no proporcionado');
        }
        $item = $this->correoModel->find($id);
        if (!$item) {
            return $this->failNotFound('Correo no encontrado');
        }

        $this->correoModel->delete($id);

        $db = \Config\Database::connect();
        if ($db->tableExists('correo')) {
            $db->table('correo')
               ->where('idcorreoelectronico', $id)
               ->orWhere('idcorreo', $id)
               ->delete();
        }

        return $this->respondDeleted([
            'status'  => 200,
            'message' => 'Correo eliminado exitosamente',
            'data'    => ['idcorreo' => $id, 'idcorreoelectronico' => $id],
        ]);
    }
}
