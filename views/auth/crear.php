<div class="crear contenedor">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <div class="descripcion-pagina">Crea una cuenta en UpTask</div>

        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
        <form action="/crear" method="POST" class="formulario">

        <div class="campo">
                <label for="nombre">Nombre:</label>
                <input 
                type="nombre" 
                name="nombre" 
                id="nombre"
                placeholder="Ingresa tu nombre"
                value="<?php echo $usuario->nombre ?>"
                >
            </div>

            <div class="campo">
                <label for="email">Email</label>
                <input 
                type="email" 
                name="email" 
                id="email"
                placeholder="Ingresa tu email"
                value="<?php echo $usuario->email ?>"
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

            <div class="campo">
                <label for="password2">Repetir Password</label>
                <input 
                type="password" 
                name="password2" 
                id="password2"
                placeholder="Repite tu password"
                >
            </div>

            <input type="submit" class="boton" value="Crear Cuenta">
        </form>

        <div class="acciones">
            <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
            <a href="/olvide">¿Olvidaste tu password?</a>
        </div>
    </div>
</div>