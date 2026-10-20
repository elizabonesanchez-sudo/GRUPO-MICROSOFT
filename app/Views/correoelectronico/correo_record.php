<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Barra de Navegación de Registros -->
<?= view('layout/nav_toolbar', [
    'module'    => 'correoelectronico',
    'currentId' => $currentId,
    'posicion'  => $posicion,
]) ?>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <?php if (!$correo): ?>
            <div class="card p-5 text-center">
                <i class="bi bi-envelope-at display-3 text-muted mb-3"></i>
                <h4 class="text-dark">No hay registros de Correo Electrónico</h4>
                <p class="text-secondary">Ingrese un nuevo correo electrónico para comenzar.</p>
                <div>
                    <a href="<?= base_url('correoelectronico/add') ?>" class="btn btn-success">
                        <i class="bi bi-plus-circle me-1"></i> Crear Correo
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold">
                        <i class="bi bi-envelope-at-fill me-2"></i>Ficha de Correo Electrónico #<?= esc($correo['idcorreoelectronico']) ?>
                    </span>
                    <span class="badge bg-light text-success"><?= esc($correo['tipo'] ?? 'Personal') ?></span>
                </div>
                <div class="card-body">
                    <div class="text-center py-3 border-bottom mb-4">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex p-3 text-success mb-2">
                            <i class="bi bi-envelope-at-fill display-5"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">
                            <a href="mailto:<?= esc($correo['correo']) ?>" class="text-decoration-none">
                                <?= esc($correo['correo']) ?>
                            </a>
                        </h4>
                        <span class="badge bg-secondary-subtle text-secondary">
                            Tipo: <?= esc($correo['tipo'] ?? 'Personal') ?>
                        </span>
                    </div>

                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-muted small text-uppercase">Código / ID:</dt>
                        <dd class="col-sm-8 fw-semibold">#<?= esc($correo['idcorreoelectronico']) ?></dd>

                        <dt class="col-sm-4 text-muted small text-uppercase">Correo:</dt>
                        <dd class="col-sm-8 fw-bold text-success fs-6"><?= esc($correo['correo']) ?></dd>

                        <dt class="col-sm-4 text-muted small text-uppercase">Tipo:</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-success-subtle text-success-emphasis">
                                <?= esc($correo['tipo'] ?? 'Personal') ?>
                            </span>
                        </dd>

                        <dt class="col-sm-4 text-muted small text-uppercase">Fecha Obtención:</dt>
                        <dd class="col-sm-8 text-muted">
                            <?= !empty($correo['fechaoptencion']) ? date('d/m/Y', strtotime($correo['fechaoptencion'])) : 'No registrada' ?>
                        </dd>

                        <dt class="col-sm-4 text-muted small text-uppercase">Persona Propietaria:</dt>
                        <dd class="col-sm-8">
                            <?php if (!empty($correo['idpersona'])): ?>
                                <a href="<?= base_url('persona/actual/' . $correo['idpersona']) ?>" class="fw-bold text-decoration-none">
                                    <i class="bi bi-person-fill me-1"></i><?= esc($correo['nombres'] . ' ' . $correo['apellidos']) ?>
                                </a>
                                <span class="badge bg-light text-secondary border ms-1">C.I. <?= esc($correo['cedula']) ?></span>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Sin asignar</span>
                            <?php endif; ?>
                        </dd>
                    </dl>
                </div>
                <div class="card-footer bg-light d-flex justify-content-between">
                    <a href="<?= base_url('correoelectronico/edit/' . $correo['idcorreoelectronico']) ?>" class="btn btn-sm btn-outline-warning text-dark">
                        <i class="bi bi-pencil-square me-1"></i> Editar
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-danger"
                            onclick="confirmarBorrado('<?= base_url('correoelectronico/delete/' . $correo['idcorreoelectronico']) ?>', '¿Desea eliminar el correo <?= esc($correo['correo']) ?>?')">
                        <i class="bi bi-trash-fill me-1"></i> Borrar
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
