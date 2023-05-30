<div class="login contenedor">
<?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <div class="descripcion-pagina">Iniciar Sesion</div>
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
        <form action="/" method="POST" class="formulario">
            <div class="campo">
                <label for="email">Email</label>
                <input 
                type="email" 
                name="email" 
                id="email"
                placeholder="Ingresa tu email"
                >
            </div>

            <div class="campo">
                <label for="password">Password</label>
                <input 
                type="password" 
                name="password" 
                id="password"
                placeholder="Ingresa tu password"
                >
            </div>

            <input type="submit" class="boton" value="Iniciar Sesión">
        </form>

        <div class="acciones">
            <a href="/crear">¿No Tienes una cuenta? Crear una</a>
            <a href="/olvide">¿Olvidaste tu password?</a>
        </div>
    </div>
</div>