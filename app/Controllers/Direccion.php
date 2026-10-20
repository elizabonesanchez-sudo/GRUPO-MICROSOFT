<?php

namespace App\Controllers;

use App\Models\DireccionModel;
use App\Models\PersonaModel;

class Direccion extends BaseController
{
    protected DireccionModel $direccionModel;
    protected PersonaModel $personaModel;

    public function __construct()
    {
        $this->direccionModel = new DireccionModel();
        $this->personaModel   = new PersonaModel();
    }

    public function index($id = null)
    {
        return $this->actual($id);
    }

    public function actual($id = null)
    {
        if ($id !== null) {
            $direccion = $this->direccionModel->getDireccionDetail($id);
        } else {
            $ultimo = $this->direccionModel->elultimo();
            $direccion = $ultimo ? $this->direccionModel->getDireccionDetail($ultimo['iddireccion']) : null;
        }

        $currentId = $direccion ? (int)$direccion['iddireccion'] : null;
        $posicion  = $currentId ? $this->direccionModel->obtenerPosicion($currentId) : ['pos' => 0, 'total' => 0];

        $data = [
            'title'     => $direccion ? 'Dirección #' . $direccion['iddireccion'] : 'Ficha de Dirección',
            'module'    => 'direccion',
            'direccion' => $direccion,
            'currentId' => $currentId,
            'posicion'  => $posicion,
        ];

        return view('direccion/direccion_record', $data);
    }

    public function elprimero()
    {
        $first = $this->direccionModel->elprimero();
        return $this->actual($first ? $first['iddireccion'] : null);
    }

    public function elultimo()
    {
        $last = $this->direccionModel->elultimo();
        return $this->actual($last ? $last['iddireccion'] : null);
    }

    public function siguiente($id)
    {
        $next = $this->direccionModel->siguiente((int)$id);
        return $this->actual($next ? $next['iddireccion'] : $id);
    }

    public function anterior($id)
    {
        $prev = $this->direccionModel->anterior((int)$id);
        return $this->actual($prev ? $prev['iddireccion'] : $id);
    }

    public function listar()
    {
        $search    = $this->request->getGet('q');
        $idpersona = $this->request->getGet('idpersona');

        $direcciones = $this->direccionModel->getDireccionesWithPersona($search, $idpersona);

        $personaFiltro = null;
        if (!empty($idpersona)) {
            $personaFiltro = $this->personaModel->find((int)$idpersona);
        }

        $data = [
            'title'         => 'Listado General de Direcciones',
            'module'        => 'direccion',
            'direcciones'   => $direcciones,
            'search'        => $search,
            'idpersona'     => $idpersona,
            'personaFiltro' => $personaFiltro,
            'total'         => count($direcciones),
        ];

        return view('direccion/direccion_list', $data);
    }

    public function add($idpersona = null)
    {
        $personas = $this->personaModel->orderBy('apellidos', 'ASC')->findAll();

        $data = [
            'title'              => 'Registrar Nueva Dirección',
            'module'             => 'direccion',
            'personas'           => $personas,
            'idpersonaSeleccion' => $idpersona ?? $this->request->getGet('idpersona'),
            'errors'             => session()->getFlashdata('errors') ?? [],
            'old'                => session()->getFlashdata('old') ?? [],
        ];

        return view('direccion/direccion_form', $data);
    }

    public function store()
    {
        $direccionTexto = trim($this->request->getPost('direccion') ?? '');
        $idpersona      = (int)$this->request->getPost('idpersona');
        $tipo           = trim($this->request->getPost('tipo') ?? 'Domicilio');

        $postData = [
            'idpersona' => $idpersona,
            'direccion' => $direccionTexto,
            'nombre'    => $direccionTexto,
            'tipo'      => !empty($tipo) ? $tipo : 'Domicilio',
        ];

        if (!$this->direccionModel->insert($postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->direccionModel->errors())
                             ->with('old', $postData);
        }

        $nuevoId = $this->direccionModel->getInsertID();

        // Si se solicitó volver a la ficha de la persona
        $retornarPersona = $this->request->getPost('retornar_persona');
        if ($retornarPersona) {
            return redirect()->to(base_url('persona/actual/' . $idpersona))
                             ->with('success', '¡Dirección agregada con éxito a la persona!');
        }

        return redirect()->to(base_url('direccion/actual/' . $nuevoId))
                         ->with('success', '¡Dirección registrada exitosamente!');
    }

    public function edit($id)
    {
        $direccion = $this->direccionModel->find((int)$id);
        if (!$direccion) {
            return redirect()->to(base_url('direccion'))->with('error', 'Dirección no encontrada.');
        }

        $personas = $this->personaModel->orderBy('apellidos', 'ASC')->findAll();

        $data = [
            'title'     => 'Editar Dirección #' . $id,
            'module'    => 'direccion',
            'direccion' => $direccion,
            'personas'  => $personas,
            'currentId' => (int)$id,
            'errors'    => session()->getFlashdata('errors') ?? [],
        ];

        return view('direccion/direccion_edit', $data);
    }

    public function update($id)
    {
        $direccion = $this->direccionModel->find((int)$id);
        if (!$direccion) {
            return redirect()->to(base_url('direccion'))->with('error', 'Registro no encontrado.');
        }

        $direccionTexto = trim($this->request->getPost('direccion') ?? '');
        $idpersona      = (int)$this->request->getPost('idpersona');
        $tipo           = trim($this->request->getPost('tipo') ?? 'Domicilio');

        $postData = [
            'idpersona' => $idpersona,
            'direccion' => $direccionTexto,
            'nombre'    => $direccionTexto,
            'tipo'      => !empty($tipo) ? $tipo : 'Domicilio',
        ];

        if (!$this->direccionModel->update((int)$id, $postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->direccionModel->errors());
        }

        $retornarPersona = $this->request->getPost('retornar_persona');
        if ($retornarPersona) {
            return redirect()->to(base_url('persona/actual/' . $idpersona))
                             ->with('success', '¡Dirección actualizada correctamente!');
        }

        return redirect()->to(base_url('direccion/actual/' . $id))
                         ->with('success', '¡Dirección actualizada exitosamente!');
    }

    public function delete($id)
    {
        $direccion = $this->direccionModel->find((int)$id);
        if (!$direccion) {
            return redirect()->to(base_url('direccion'))->with('error', 'Registro no encontrado.');
        }

        $idpersona = $direccion['idpersona'];
        $this->direccionModel->delete((int)$id);

        $fromPersona = $this->request->getGet('from_persona');
        if ($fromPersona) {
            return redirect()->to(base_url('persona/actual/' . $idpersona))
                             ->with('success', 'Dirección eliminada de la persona.');
        }

        $ultimo = $this->direccionModel->elultimo();
        $redirectUrl = $ultimo ? base_url('direccion/actual/' . $ultimo['iddireccion']) : base_url('direccion');

        return redirect()->to($redirectUrl)
                         ->with('success', 'Dirección #' . $id . ' eliminada exitosamente.');
    }
}
