<?php
/**
 * Componente de Barra de Navegación Universal (Nav Toolbar)
 * Parámetros esperados:
 * - $module: string (ej: 'persona', 'sexo', 'estadocivil', 'direccion', 'correoelectronico')
 * - $currentId: int|null
 * - $posicion: array ['pos' => int, 'total' => int]
 */
?>
<div class="nav-toolbar-card p-3 mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        
        <!-- Bloque 1: Navegación registro a registro -->
        <div class="d-flex align-items-center flex-wrap gap-2">
            <span class="text-muted small fw-semibold text-uppercase d-none d-md-inline">
                <i class="bi bi-arrows-expand me-1"></i>Navegar:
            </span>
            <div class="btn-group btn-group-sm" role="group">
                <a href="<?= base_url($module . '/elprimero') ?>" class="btn btn-outline-primary" title="Ir al Primer Registro">
                    <i class="bi bi-chevron-bar-left"></i> Primero
                </a>
                <a href="<?= base_url($module . '/anterior/' . ($currentId ?? 0)) ?>" class="btn btn-outline-primary" title="Registro Anterior">
                    <i class="bi bi-chevron-left"></i> Anterior
                </a>
                <a href="<?= base_url($module . '/siguiente/' . ($currentId ?? 0)) ?>" class="btn btn-outline-primary" title="Registro Siguiente">
                    Siguiente <i class="bi bi-chevron-right"></i>
                </a>
                <a href="<?= base_url($module . '/elultimo') ?>" class="btn btn-outline-primary" title="Ir al Último Registro">
                    Último <i class="bi bi-chevron-bar-right"></i>
                </a>
            </div>

            <?php if (!empty($posicion) && isset($posicion['total']) && $posicion['total'] > 0): ?>
                <span class="badge bg-secondary-subtle text-secondary border px-2 py-1 ms-1">
                    Registro <strong><?= esc($posicion['pos']) ?></strong> de <strong><?= esc($posicion['total']) ?></strong>
                </span>
            <?php endif; ?>
        </div>

        <!-- Bloque 2: Operaciones CRUD de gestión -->
        <div class="d-flex align-items-center gap-2">
            <div class="btn-group btn-group-sm" role="group">
                <a href="<?= base_url($module . '/add') ?>" class="btn btn-success" title="Registrar Nuevo">
                    <i class="bi bi-plus-circle me-1"></i> Nuevo
                </a>
                <?php if (!empty($currentId)): ?>
                    <a href="<?= base_url($module . '/edit/' . $currentId) ?>" class="btn btn-warning text-dark fw-semibold" title="Editar Registro Actual">
                        <i class="bi bi-pencil-square me-1"></i> Editar
                    </a>
                    <button type="button" class="btn btn-danger" title="Eliminar Registro Actual"
                            onclick="confirmarBorrado('<?= base_url($module . '/delete/' . $currentId) ?>', 'Se eliminará el registro #<?= esc($currentId) ?> permanentemente.')">
                        <i class="bi bi-trash-fill me-1"></i> Borrar
                    </button>
                <?php endif; ?>
            </div>

            <a href="<?= base_url($module . '/listar') ?>" class="btn btn-info btn-sm text-white fw-semibold" title="Ver Lista de Todos los Registros">
                <i class="bi bi-table me-1"></i> Listar Todos
            </a>
        </div>

    </div>
</div>
