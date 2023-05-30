<?php

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;

class DashBoardController
{


    public static function index(Router $router)
    {
        // Iniciar la sesión del usuario
        if (!isset($_SESSION)) {

            session_start();
        }
        isAuth();
        $id = $_SESSION['id'];
        $proyectos = Proyecto::belongsTo('propietarioId', $id);



        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }

    public static function crear_proyecto(Router $router)
    {

        $alertas = [];
        // Iniciar la sesión del usuario
        if (!isset($_SESSION)) {

            session_start();
        }
        isAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $proyecto = new Proyecto($_POST);
            // Validacion
            $alertas = $proyecto->validarProyecto();

            if (empty($alertas)) {
                // Generar URL unica
                $hash = md5(uniqid());
                $proyecto->url = $hash;
                //Almacenar el creador del proyecto
                $proyecto->propietarioId = $_SESSION['id'];

                // Guardar el Proyecto
                $proyecto->guardar();
                // Redireccionar
                header('Location: /proyecto?id=' . $proyecto->url);
            }
        }


        $router->render('dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }


    public static function proyecto(Router $router)
    {
        $alertas = [];
        // Iniciar la sesión del usuario
        if (!isset($_SESSION)) {

            session_start();
        }
        isAuth();
        // Revisar que la persona que visita el proyecto es el creador
        $token = $_GET['id'];

        if (!$token) header('Location: /dashboard');

        $proyecto = Proyecto::where('url', $token);

        if ($proyecto->propietarioId !== $_SESSION['id']) {
            header('Location: /dashboard');
        }


        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto,
            'alertas' => $alertas
        ]);
    }

    public static function perfil(Router $router)
    {
        // Iniciar la sesión del usuario
        if (!isset($_SESSION)) {

            session_start();
        }
        isAuth();
        $alertas = [];
        $usuario = Usuario::find($_SESSION['id']);


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validar_perfil();

            if (empty($alertas)) {

                $existeUsuario = Usuario::where('email', $usuario->email);

                if ($existeUsuario && $existeUsuario->id !== $usuario->id) {
                    // Mostrar un mensaje de error
                    Usuario::setAlerta('error', 'Email no válido, este email ya esta registrado');
                } else {
                    // Guardar el Registro
                    $usuario->guardar();

                    Usuario::setAlerta('exito', 'Cambios realizados correctamente');

                    $alertas = $usuario->getAlertas();
                    // Asignar el nombre nuevo a la session y a la barra
                    $_SESSION['nombre'] = $usuario->nombre;
                }
            }
        }

        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }


    public static function cambiar_password(Router $router)
    {
        // Iniciar la sesión del usuario
        if (!isset($_SESSION)) {

            session_start();
        }
        isAuth();
        $alertas = [];


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = Usuario::find($_SESSION['id']);


            // Sincronizar con los datos del usuario
            $usuario->sincronizar($_POST);

            $alertas = $usuario->nuevo_password();


            if (empty($alertas)) {
                $resultado = $usuario->comprobar_password();

                if ($resultado) {
                    // Asignar el nuevo password
                    
                    $usuario->password = $usuario->password_nuevo;
                    // Eliminar Propiedades No Necesarias
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);

                    // Hash al nuevo password

                    $usuario->hashPassword();
                    // Actualizar

                    $resultado = $usuario->guardar();
                    if($resultado) {
                        Usuario::setAlerta('exito', 'Password Cambiado Correctamente');
                    $alertas = $usuario->getAlertas();
                    }


                } else {
                    Usuario::setAlerta('error', 'Password Incorrecto');
                    $alertas = $usuario->getAlertas();
                }
            }
        }




        $router->render('dashboard/cambiar-password', [
            'titulo' => 'Cambiar Password',
            'alertas' => $alertas
        ]);
    }
}
