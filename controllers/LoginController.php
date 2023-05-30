<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;



class LoginController
{
    public static function login(Router $router)
    {
        

        $alertas = [];


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);

            $alertas = $usuario->validarLogin();


            if(empty($alertas)) {
                // Verificar que el usuario exista
                $usuario = Usuario::where('email', $usuario->email);
                

                if(!$usuario || !$usuario->confirmado) {
                    Usuario::setAlerta('error', 'El Usuario No Existe o no esta Confirmado');
                } else {
                    // El usuario existe Revisar password

                    if(password_verify($_POST['password'], $usuario->password)) {
                        // Iniciar la sesión del usuario
                        if(!isset($_SESSION)) {

                            session_start();

                            
                        }
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        //Redireccionar
                        header('Location: /dashboard');
                        

                    } else {
                        Usuario::setAlerta('error', 'Password Incorrecto');
                    }

                }
                
                



            }

        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesion',
            'alertas' => $alertas
        ]);
    }


    public static function logout()
    {
        if (!isset($_SESSION)) {

            session_start();
        }

        $_SESSION = [];
        header('Location: /');
    }


    public static function crear(Router $router)
    {
        $alertas = [];
        $usuario = new Usuario;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            if (empty($alertas)) {
                $existeUsuario = Usuario::where('email', $usuario->email);

                if ($existeUsuario) {
                    Usuario::setAlerta('error', 'Este Email ya esta registrado');
                    $alertas = Usuario::getAlertas();
                } else {
                    // Hashear el password
                    $usuario->hashPassword();

                    // Eliminar password2
                    unset($usuario->password2);

                    // Generar el token
                    $usuario->crearToken();



                    // Crear un nuevo usuario
                    $resultado = $usuario->guardar();

                    //Enviar Email

                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    if ($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }
        $router->render('auth/crear', [
            'titulo' => 'Crear Cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function olvide(Router $router)
    {
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if (empty($alertas)) {
                // Buscar el usuario
                $usuario = Usuario::where('email', $usuario->email);

                if ($usuario && $usuario->confirmado === "1") {
                    // Generar un nuevo token
                    $usuario->crearToken();
                    unset($usuario->password2);

                    // Actualizar el usuario
                    $usuario->guardar();

                    // Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();
                    // Imprimir la alerta

                    Usuario::setAlerta('exito', 'Hemos enviado las intrucciones a tu email');
                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }
            }
        }
        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide', [
            'titulo' => 'Recuperar Password',
            'alertas' => $alertas
        ]);
    }
    public static function reestablecer(Router $router)
    {
        $alertas = [];
        $token = s($_GET['token']);
        $mostrar = true;

        if(!$token) header('Location: /');

        // Identificar el usuario con este token
        $usuario = Usuario::where('token', $token);
        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token no Válido');
            $mostrar = false;
        }
        

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Añadir el nuevo password
            $usuario->sincronizar($_POST);

            // Validar el password

            $alertas = $usuario->validarPassword();

            if(empty($alertas)){
                // Hashear el nuevo password
                $usuario->hashPassword();
                unset($usuario->password2);
                // Eliminar el token
                $usuario->token = '';
               
                // Guardar el usuario en la base de datos
                $resultado = $usuario->guardar();
                //Redireccionar
                if($resultado){
                    header('Location: /');
                }


            }

        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer password',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }

    public static function mensaje(Router $router)
    {

        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta creada exitosamente'
        ]);
    }

    public static function confirmar(Router $router)
    {

        $token = s($_GET['token']);

        if (!$token) header('Location: /');


        // Encontrar al usuario con este token 

        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            //No se encontre un usuario con ese token
            Usuario::setAlerta('error', 'Token No Válido');
        } else {
            // Confirmar la cuenta
            $usuario->confirmado = 1;
            $usuario->token = '';
            unset($usuario->password2);
            // Guardar en la Base de datos
            $usuario->guardar();

            Usuario::setAlerta('exito', 'Cuenta Confirmada Correctamente');
        }



        $alertas = Usuario::getAlertas();

        $router->render('auth/confirmar', [
            'titulo' => 'Confirmando',
            'alertas' => $alertas
        ]);
    }
};
