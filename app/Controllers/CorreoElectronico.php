<?php

namespace App\Controllers;

use App\Models\CorreoElectronicoModel;
use App\Models\PersonaModel;

class CorreoElectronico extends BaseController
{
    protected CorreoElectronicoModel $correoModel;
    protected PersonaModel $personaModel;

    public function __construct()
    {
        $this->correoModel  = new CorreoElectronicoModel();
        $this->personaModel = new PersonaModel();
    }

    public function index($id = null)
    {
        return $this->actual($id);
    }

    public function actual($id = null)
    {
        if ($id !== null) {
            $correo = $this->correoModel->getCorreoDetail($id);
        } else {
            $ultimo = $this->correoModel->elultimo();
            $correo = $ultimo ? $this->correoModel->getCorreoDetail($ultimo['idcorreoelectronico']) : null;
        }

        $currentId = $correo ? (int)$correo['idcorreoelectronico'] : null;
        $posicion  = $currentId ? $this->correoModel->obtenerPosicion($currentId) : ['pos' => 0, 'total' => 0];

        $data = [
            'title'     => $correo ? 'Correo: ' . esc($correo['correo']) : 'Ficha de Correo Electrónico',
            'module'    => 'correoelectronico',
            'correo'    => $correo,
            'currentId' => $currentId,
            'posicion'  => $posicion,
        ];

        return view('correoelectronico/correo_record', $data);
    }

    public function elprimero()
    {
        $first = $this->correoModel->elprimero();
        return $this->actual($first ? $first['idcorreoelectronico'] : null);
    }

    public function elultimo()
    {
        $last = $this->correoModel->elultimo();
        return $this->actual($last ? $last['idcorreoelectronico'] : null);
    }

    public function siguiente($id)
    {
        $next = $this->correoModel->siguiente((int)$id);
        return $this->actual($next ? $next['idcorreoelectronico'] : $id);
    }

    public function anterior($id)
    {
        $prev = $this->correoModel->anterior((int)$id);
        return $this->actual($prev ? $prev['idcorreoelectronico'] : $id);
    }

    public function listar()
    {
        $search    = $this->request->getGet('q');
        $idpersona = $this->request->getGet('idpersona');

        $correos = $this->correoModel->getCorreosWithPersona($search, $idpersona);

        $personaFiltro = null;
        if (!empty($idpersona)) {
            $personaFiltro = $this->personaModel->find((int)$idpersona);
        }

        $data = [
            'title'         => 'Listado General de Correos Electrónicos',
            'module'        => 'correoelectronico',
            'correos'       => $correos,
            'search'        => $search,
            'idpersona'     => $idpersona,
            'personaFiltro' => $personaFiltro,
            'total'         => count($correos),
        ];

        return view('correoelectronico/correo_list', $data);
    }

    public function add($idpersona = null)
    {
        $personas = $this->personaModel->orderBy('apellidos', 'ASC')->findAll();

        $data = [
            'title'              => 'Registrar Nuevo Correo Electrónico',
            'module'             => 'correoelectronico',
            'personas'           => $personas,
            'idpersonaSeleccion' => $idpersona ?? $this->request->getGet('idpersona'),
            'errors'             => session()->getFlashdata('errors') ?? [],
            'old'                => session()->getFlashdata('old') ?? [],
        ];

        return view('correoelectronico/correo_form', $data);
    }

    public function store()
    {
        $correoTexto    = trim($this->request->getPost('correo') ?? '');
        $idpersona      = (int)$this->request->getPost('idpersona');
        $tipo           = trim($this->request->getPost('tipo') ?? 'Personal');
        $fechaoptencion = !empty($this->request->getPost('fechaoptencion')) ? $this->request->getPost('fechaoptencion') : date('Y-m-d');

        $postData = [
            'idpersona'      => $idpersona,
            'correo'         => $correoTexto,
            'nombre'         => $correoTexto,
            'tipo'           => !empty($tipo) ? $tipo : 'Personal',
            'fechaoptencion' => $fechaoptencion,
        ];

        if (!$this->correoModel->insert($postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->correoModel->errors())
                             ->with('old', $postData);
        }

        $nuevoId = $this->correoModel->getInsertID();

        // Si se solicitó volver a la ficha de la persona
        $retornarPersona = $this->request->getPost('retornar_persona');
        if ($retornarPersona) {
            return redirect()->to(base_url('persona/actual/' . $idpersona))
                             ->with('success', '¡Correo electrónico añadido con éxito a la persona!');
        }

        return redirect()->to(base_url('correoelectronico/actual/' . $nuevoId))
                         ->with('success', '¡Correo electrónico registrado exitosamente!');
    }

    public function edit($id)
    {
        $correo = $this->correoModel->find((int)$id);
        if (!$correo) {
            return redirect()->to(base_url('correoelectronico'))->with('error', 'Correo no encontrado.');
        }

        $personas = $this->personaModel->orderBy('apellidos', 'ASC')->findAll();

        $data = [
            'title'     => 'Editar Correo: ' . esc($correo['correo']),
            'module'    => 'correoelectronico',
            'correo'    => $correo,
            'personas'  => $personas,
            'currentId' => (int)$id,
            'errors'    => session()->getFlashdata('errors') ?? [],
        ];

        return view('correoelectronico/correo_edit', $data);
    }

    public function update($id)
    {
        $correo = $this->correoModel->find((int)$id);
        if (!$correo) {
            return redirect()->to(base_url('correoelectronico'))->with('error', 'Registro no encontrado.');
        }

        $correoTexto    = trim($this->request->getPost('correo') ?? '');
        $idpersona      = (int)$this->request->getPost('idpersona');
        $tipo           = trim($this->request->getPost('tipo') ?? 'Personal');
        $fechaoptencion = !empty($this->request->getPost('fechaoptencion')) ? $this->request->getPost('fechaoptencion') : null;

        $postData = [
            'idpersona'      => $idpersona,
            'correo'         => $correoTexto,
            'nombre'         => $correoTexto,
            'tipo'           => !empty($tipo) ? $tipo : 'Personal',
            'fechaoptencion' => $fechaoptencion,
        ];

        if (!$this->correoModel->update((int)$id, $postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->correoModel->errors());
        }

        $retornarPersona = $this->request->getPost('retornar_persona');
        if ($retornarPersona) {
            return redirect()->to(base_url('persona/actual/' . $idpersona))
                             ->with('success', '¡Correo actualizado correctamente!');
        }

        return redirect()->to(base_url('correoelectronico/actual/' . $id))
                         ->with('success', '¡Correo electrónico actualizado exitosamente!');
    }

    public function delete($id)
    {
        $correo = $this->correoModel->find((int)$id);
        if (!$correo) {
            return redirect()->to(base_url('correoelectronico'))->with('error', 'Registro no encontrado.');
        }

        $idpersona = $correo['idpersona'];
        $this->correoModel->delete((int)$id);

        $fromPersona = $this->request->getGet('from_persona');
        if ($fromPersona) {
            return redirect()->to(base_url('persona/actual/' . $idpersona))
                             ->with('success', 'Correo electrónico eliminado de la persona.');
        }

        $ultimo = $this->correoModel->elultimo();
        $redirectUrl = $ultimo ? base_url('correoelectronico/actual/' . $ultimo['idcorreoelectronico']) : base_url('correoelectronico');

        return redirect()->to($redirectUrl)
                         ->with('success', 'Correo electrónico #' . $id . ' eliminado exitosamente.');
    }
}
