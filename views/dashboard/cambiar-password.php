<?php include_once __DIR__ . '/header-dashboard.php' ?>


<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php' ?>
    <a href="/perfil" class="enlace">Volver al Perfil</a>
    <form action="/cambiar-password" class="formulario" method="POST">
        <div class="campo">
            <label for="passwordactual">Password Actual:</label>
            <input type="password" name="password_actual" id="passwordactual" placeholder="Tu Password Actual">
        </div>

        <div class="campo">
            <label for="passwordnuevo">Password Nuevo:</label>
            <input type="password" name="password_nuevo" id="passwordnuevo" placeholder="Tu Password Nuevo">
        </div>

        <input type="submit" value="Guardar Cambios">
    </form>
</div>


<?php include_once __DIR__ . '/footer-dashboard.php' ?>