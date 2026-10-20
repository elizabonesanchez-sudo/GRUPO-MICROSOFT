<?php

namespace App\Controllers;

use App\Models\PersonaModel;
use App\Models\SexoModel;
use App\Models\EstadoCivilModel;
use App\Models\DireccionModel;
use App\Models\CorreoElectronicoModel;

class Persona extends BaseController
{
    protected PersonaModel $personaModel;
    protected SexoModel $sexoModel;
    protected EstadoCivilModel $estadoCivilModel;
    protected DireccionModel $direccionModel;
    protected CorreoElectronicoModel $correoModel;

    public function __construct()
    {
        $this->personaModel     = new PersonaModel();
        $this->sexoModel        = new SexoModel();
        $this->estadoCivilModel = new EstadoCivilModel();
        $this->direccionModel   = new DireccionModel();
        $this->correoModel      = new CorreoElectronicoModel();
    }

    /**
     * Entrada principal al módulo Persona
     */
    public function index($id = null)
    {
        return $this->actual($id);
    }

    /**
     * Ficha de registro individual con barra de navegación
     */
    public function actual($id = null)
    {
        if ($id !== null) {
            $persona = $this->personaModel->getPersonaDetail($id);
        } else {
            $ultimo = $this->personaModel->elultimo();
            $persona = $ultimo ? $this->personaModel->getPersonaDetail($ultimo['idpersona']) : null;
        }

        if (!$persona) {
            $data = [
                'title'          => 'Ficha de Persona',
                'module'         => 'persona',
                'persona'        => null,
                'currentId'      => null,
                'posicion'       => ['pos' => 0, 'total' => 0],
                'direcciones'    => [],
                'correos'        => [],
                'catalogoSexo'   => $this->sexoModel->findAll(),
                'catalogoEC'     => $this->estadoCivilModel->findAll(),
            ];
            return view('persona/persona_record', $data);
        }

        $idpersona = (int)$persona['idpersona'];
        $posicion  = $this->personaModel->obtenerPosicion($idpersona);

        $data = [
            'title'          => 'Persona: ' . esc($persona['nombres'] . ' ' . $persona['apellidos']),
            'module'         => 'persona',
            'persona'        => $persona,
            'currentId'      => $idpersona,
            'posicion'       => $posicion,
            'direcciones'    => $this->direccionModel->getDireccionesByPersona($idpersona),
            'correos'        => $this->correoModel->getCorreosByPersona($idpersona),
            'catalogoSexo'   => $this->sexoModel->findAll(),
            'catalogoEC'     => $this->estadoCivilModel->findAll(),
        ];

        return view('persona/persona_record', $data);
    }

    /**
     * Ir al primer registro
     */
    public function elprimero()
    {
        $first = $this->personaModel->elprimero();
        return $this->actual($first ? $first['idpersona'] : null);
    }

    /**
     * Ir al último registro
     */
    public function elultimo()
    {
        $last = $this->personaModel->elultimo();
        return $this->actual($last ? $last['idpersona'] : null);
    }

    /**
     * Ir al registro siguiente
     */
    public function siguiente($id)
    {
        $next = $this->personaModel->siguiente((int)$id);
        return $this->actual($next ? $next['idpersona'] : $id);
    }

    /**
     * Ir al registro anterior
     */
    public function anterior($id)
    {
        $prev = $this->personaModel->anterior((int)$id);
        return $this->actual($prev ? $prev['idpersona'] : $id);
    }

    /**
     * Listado tabular de personas
     */
    public function listar()
    {
        $search = $this->request->getGet('q');
        $personas = $this->personaModel->getPersonasOrdenadas($search);

        $data = [
            'title'    => 'Listado General de Personas',
            'module'   => 'persona',
            'personas' => $personas,
            'search'   => $search,
            'total'    => count($personas),
        ];

        return view('persona/persona_list', $data);
    }

    /**
     * Formulario para crear una nueva persona
     */
    public function add()
    {
        $data = [
            'title'        => 'Registrar Nueva Persona',
            'module'       => 'persona',
            'catalogoSexo' => $this->sexoModel->findAll(),
            'catalogoEC'   => $this->estadoCivilModel->findAll(),
            'errors'       => session()->getFlashdata('errors') ?? [],
            'old'          => session()->getFlashdata('old') ?? [],
        ];

        return view('persona/persona_form', $data);
    }

    /**
     * Guardar una nueva persona
     */
    public function store()
    {
        $postData = [
            'cedula'          => trim($this->request->getPost('cedula') ?? ''),
            'nombres'         => trim($this->request->getPost('nombres') ?? ''),
            'apellidos'       => trim($this->request->getPost('apellidos') ?? ''),
            'fechanacimiento' => !empty($this->request->getPost('fechanacimiento')) ? $this->request->getPost('fechanacimiento') : null,
            'idsexo'          => !empty($this->request->getPost('idsexo')) ? (int)$this->request->getPost('idsexo') : null,
            'idestadocivil'   => !empty($this->request->getPost('idestadocivil')) ? (int)$this->request->getPost('idestadocivil') : null,
        ];

        if (!$this->personaModel->insert($postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->personaModel->errors())
                             ->with('old', $postData);
        }

        $nuevoId = $this->personaModel->getInsertID();

        // Si se ingresó una dirección inicial en el mismo formulario
        $direccionInicial = trim($this->request->getPost('direccion_inicial') ?? '');
        if (!empty($direccionInicial)) {
            $this->direccionModel->insert([
                'idpersona' => $nuevoId,
                'direccion' => $direccionInicial,
                'nombre'    => $direccionInicial,
                'tipo'      => $this->request->getPost('tipo_direccion') ?? 'Domicilio',
            ]);
        }

        // Si se ingresó un correo inicial en el mismo formulario
        $correoInicial = trim($this->request->getPost('correo_inicial') ?? '');
        if (!empty($correoInicial)) {
            $this->correoModel->insert([
                'idpersona'      => $nuevoId,
                'correo'         => $correoInicial,
                'nombre'         => $correoInicial,
                'tipo'           => $this->request->getPost('tipo_correo') ?? 'Personal',
                'fechaoptencion' => date('Y-m-d'),
            ]);
        }

        return redirect()->to(base_url('persona/actual/' . $nuevoId))
                         ->with('success', '¡Persona registrada exitosamente con código #' . $nuevoId . '!');
    }

    /**
     * Formulario de edición
     */
    public function edit($id)
    {
        $persona = $this->personaModel->find((int)$id);
        if (!$persona) {
            return redirect()->to(base_url('persona'))->with('error', 'La persona solicitada no existe.');
        }

        $data = [
            'title'        => 'Editar Persona: ' . esc($persona['nombres'] . ' ' . $persona['apellidos']),
            'module'       => 'persona',
            'persona'      => $persona,
            'currentId'    => (int)$id,
            'catalogoSexo' => $this->sexoModel->findAll(),
            'catalogoEC'   => $this->estadoCivilModel->findAll(),
            'errors'       => session()->getFlashdata('errors') ?? [],
        ];

        return view('persona/persona_edit', $data);
    }

    /**
     * Actualizar persona
     */
    public function update($id)
    {
        $persona = $this->personaModel->find((int)$id);
        if (!$persona) {
            return redirect()->to(base_url('persona'))->with('error', 'Registro no encontrado.');
        }

        $postData = [
            'cedula'          => trim($this->request->getPost('cedula') ?? ''),
            'nombres'         => trim($this->request->getPost('nombres') ?? ''),
            'apellidos'       => trim($this->request->getPost('apellidos') ?? ''),
            'fechanacimiento' => !empty($this->request->getPost('fechanacimiento')) ? $this->request->getPost('fechanacimiento') : null,
            'idsexo'          => !empty($this->request->getPost('idsexo')) ? (int)$this->request->getPost('idsexo') : null,
            'idestadocivil'   => !empty($this->request->getPost('idestadocivil')) ? (int)$this->request->getPost('idestadocivil') : null,
        ];

        // Validar unicidad de cédula excluyendo el registro actual
        $this->personaModel->setValidationRule('cedula', "required|min_length[5]|max_length[20]|is_unique[persona.cedula,idpersona,{$id}]");

        if (!$this->personaModel->update((int)$id, $postData)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->personaModel->errors());
        }

        return redirect()->to(base_url('persona/actual/' . $id))
                         ->with('success', '¡Datos de la persona actualizados correctamente!');
    }

    /**
     * Eliminar persona
     */
    public function delete($id)
    {
        $persona = $this->personaModel->find((int)$id);
        if (!$persona) {
            return redirect()->to(base_url('persona'))->with('error', 'Registro no encontrado para eliminar.');
        }

        $this->personaModel->delete((int)$id);

        $ultimo = $this->personaModel->elultimo();
        $redirectUrl = $ultimo ? base_url('persona/actual/' . $ultimo['idpersona']) : base_url('persona');

        return redirect()->to($redirectUrl)
                         ->with('success', 'Persona #' . $id . ' y sus registros relacionados fueron eliminados correctamente.');
    }
}
