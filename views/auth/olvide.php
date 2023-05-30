<div class="olvide contenedor">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <div class="descripcion-pagina">Enviar correo de recuperacion</div>
    
        <form action="/olvide" method="POST" class="formulario">

       
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
            <div class="campo">
                <label for="email">Email</label>
                <input 
                type="email" 
                name="email" 
                id="email"
                placeholder="Ingresa tu email"
                >
            </div>


            <input type="submit" class="boton" value="Recuperar password">
        </form>

        <div class="acciones">
            <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
            <a href="/crear">¿No Tienes una cuenta? Crear una</a>
        </div>
    </div>
</div>