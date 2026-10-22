<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Cliente</title>
</head>
<body>
    <h2>Registro de Cliente</h2>
    
    <form action="<?= base_url('cliente/guardar') ?>" method="post">
        <!-- Token de seguridad obligatorio contra ataques CSRF -->
        <?= csrf_field() ?>

        <div>
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>
        <br>
        <div>
            <label for="correo">Correo Electrónico:</label>
            <input type="email" id="correo" name="correo" required>
        </div>
        <br>
        <button type="submit">Guardar Cliente</button>
    </form>
</body>
</html>