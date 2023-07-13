<?php

namespace Clases;

use PHPMailer\PHPMailer\PHPMailer;

class Email{
    
    public $email;
    public $nombre;
    public $token;

    public function __construct ($email, $nombre, $token){
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    //Enviar correo de confirmación
    public function enviarConfirmacion(){
        //creamos la instancia
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->MAIL_ENCRYPTION = 'tls';
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        //Recipients o destinatarios
        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'uptask.com');
        $mail->Subject = 'Confirma tu cuenta';

        //Content HTML
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $content = "<html>";
        $content .= "<p><strong>Hola " . $this->email . " " . $this->nombre;
        $content .= " </strong> Has creado una cuenta en uptask, ";
        $content .= "solo debes confirmarla en el siguiente enlace</P>";
        $content .= "<p>Presiona aquí: <a href='". $_ENV['APP_URL'] ."/confirmar?token="
         . $this->token . "' >Confirmar Cuenta</a></p>";
        $content .= "<p>Si no solicitaste el cambio, puedes ignorar el mensaje </p>";
        $content .= "</HTML";
        
        $mail->Body = $content;

        $mail->send();

    }

    public function reestablecer(){
                //creamos la instancia
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->MAIL_ENCRYPTION = 'tls';
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        //Recipients o destinatarios
        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'uptask.com');
        $mail->Subject = 'Reestablecer Password';

        //Content HTML
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $content = "<html>";
        $content .= "<p><strong>Hola " . $this->email . " " . $this->nombre;
        $content .= " </strong> Has solicitado reestablecer tu password en uptask, ";
        $content .= "solo debes seguir el siguiente enlace</P>";
        $content .= "<p>Presiona aquí: <a href='". $_ENV['APP_URL'] ."/reestablecer?token="
         . $this->token . "' >Reestablecer password</a></p>";
        $content .= "<p>Si no solicitaste el cambio, puedes ignorar el mensaje </p>";
        $content .= "</HTML";
        
        $mail->Body = $content;

        $mail->send();
    }
}