<div class="reestablecer contenedor">
<?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <div class="descripcion-pagina">Reestablece tu password</div>
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
        <form method="POST" class="formulario">

        <?php if($mostrar) { ?>
            <div class="campo">
                <label for="password">Password</label>
                <input 
                type="password" 
                name="password" 
                id="password"
                placeholder="Ingresa tu nuevo password"
                >
            </div>

            <input type="submit" class="boton" value="Guardar password">
        </form>
<?php } ?>
        <div class="acciones">
            <a href="/crear">¿No Tienes una cuenta? Crear una</a>
            <a href="/olvide">¿Olvidaste tu password?</a>
        </div>
    </div>
</div>