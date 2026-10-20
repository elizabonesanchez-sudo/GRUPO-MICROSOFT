<?php

namespace App\Models;

trait NavigableTrait
{
    /**
     * Obtener el primer registro ordenado por clave primaria ascendente
     */
    public function elprimero()
    {
        return $this->orderBy($this->primaryKey, 'ASC')->first();
    }

    /**
     * Obtener el último registro ordenado por clave primaria descendente
     */
    public function elultimo()
    {
        return $this->orderBy($this->primaryKey, 'DESC')->first();
    }

    /**
     * Obtener el registro siguiente al ID especificado
     */
    public function siguiente($id)
    {
        $next = $this->where($this->primaryKey . ' >', (int)$id)
                     ->orderBy($this->primaryKey, 'ASC')
                     ->first();

        return $next ?: $this->find((int)$id) ?: $this->elultimo();
    }

    /**
     * Obtener el registro anterior al ID especificado
     */
    public function anterior($id)
    {
        $prev = $this->where($this->primaryKey . ' <', (int)$id)
                     ->orderBy($this->primaryKey, 'DESC')
                     ->first();

        return $prev ?: $this->find((int)$id) ?: $this->elprimero();
    }

    /**
     * Obtener el total de registros en la tabla
     */
    public function totalRegistros()
    {
        return $this->countAllResults();
    }

    /**
     * Obtener posición (1-based index) de un ID dado
     */
    public function obtenerPosicion($id)
    {
        $ids = $this->orderBy($this->primaryKey, 'ASC')->findColumn($this->primaryKey) ?: [];
        $index = array_search((int)$id, $ids, true);
        return [
            'pos'   => ($index !== false) ? $index + 1 : 1,
            'total' => count($ids),
        ];
    }
}
