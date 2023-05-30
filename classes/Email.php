<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;
use SplSubject;

class Email
{

    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }



    public function enviarConfirmacion()
    {

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '025cb332803d19';
        $mail->Password = '6a3a0948e42892';

        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'uptask.com');
        $mail->Subject = 'Confirma tu Cuenta';

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has creado tu cuenta en Uptask, solo debes confimarla en el siguente enlace.</p>";
        $contenido .= "<p>Haz click en este enlace: <a href='http://localhost:8000/confirmar?token=" . $this->token . "'>Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si tu no solicitaste este mensaje puedes ignorarlo.</p>";
        $contenido .= '</html>';

        $mail->Body = $contenido;

        //Enviar mail

        $mail->send();
    }

    public function enviarInstrucciones()
    {

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '025cb332803d19';
        $mail->Password = '6a3a0948e42892';

        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'uptask.com');
        $mail->Subject = 'Reestablece tu Password';

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Si olvidaste tu password abajo estan las instrucciones para reestablecerla</p>";
        $contenido .= "<p>Haz click en este enlace: <a href='http://localhost:8000/reestablecer?token=" . $this->token . "'>Reestablecer Password</a></p>";
        $contenido .= "<p>Si tu no solicitaste este mensaje puedes ignorarlo.</p>";
        $contenido .= '</html>';

        $mail->Body = $contenido;

        //Enviar mail

        $mail->send();
    }
}
