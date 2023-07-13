<?php

namespace Controllers;
use MVC\Router;
use Model\Usuario;
use Clases\Email;

class LoginController{
    public static function login(Router $router){

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $usuario = new Usuario($_POST);

            $alertas = $usuario->validarLogin();

            if(empty($alertas)){

                //Comprobar que el usuario existe
                $usuario = Usuario::where('email', $usuario->email);
                
                if(!$usuario || !$usuario->confirmado){
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }else{
                    //Comprobar password
                    if(password_verify($_POST['password'], $usuario->password)){
                        //iniciar sesión
                        session_start();
                        //Pasamos las variables
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        //Reedireccionar al usuario
                        header('Location: /dashboard');
                    }else{
                        Usuario::setAlerta('error', 'El password es incorrecto');
                    }
                }

                //\debuguear($usuario);
            }

        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas
        ]);
    }

    public static function logout(){
        //Traemos toda la información del usuario y la despejamos.
        session_start();
        $_SESSION = [];
        header('Location: /');

    }

    public static function crear(Router $router){

        $usuario = new Usuario;
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //Sincronizar el post con la db
            $usuario->sincronizar($_POST);
            //Comprueba errores en campos
            $alertas = $usuario->validarNuevaCuenta();

            if(empty($alertas)){
                //Compoprbar si el usuario exite
                $existeUsuario = Usuario::where('email', $usuario->email);

                if($existeUsuario){
                    Usuario::setAlerta('error', 'El usuario ya está registrado');
                    //Almacenamos la alerta en una variable
                    $alertas = Usuario::getAlertas();
                }else{
                    //Hashear password
                    $usuario->hashPassword();
                    //Eliminar objeto de comprobación
                    unset($usuario->password2);
                    //Crear token
                    $usuario->crearToken();
                    //Crear nuevo usuario
                    $resultado = $usuario->guardar();
                    //Enviar email de confirmación
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();
                    if($resultado){
                        //Redireccionar al usuario
                        header('Location: /mensaje');
                    }
                }
   
            }
        }

        $router->render('auth/crear', [
            'titulo' => 'Crea tu cuenta en uptask',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function olvido(Router $router){

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $usuario = new Usuario($_POST);
            
            $alertas = $usuario->validarEmail();
            
            if(empty($alertas)){

                $usuario = Usuario::where('email', $usuario->email);
                
                if($usuario && $usuario->confirmado){
                    
                    //Eliminar indice del objeto
                    unset($usuario->password2);
                    //Creamos token
                    $usuario->token = md5(uniqid());
                    //Enviar email
                    $correo = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $correo->reestablecer();
                    //Reestablecer password
                    $resultado = $usuario->guardar();
                    if($resultado){
                        //Reedireccionar al usuario
                        header('Location: /mensaje');
                    }
                }else{
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                    $alertas = Usuario::getAlertas();
                }   
            }
        }

        $router->render('auth/olvido', [
            'titulo' => 'Recupera tu password',
            'alertas' => $alertas
        ]);
    }

    public static function reestablecer(Router $router){

        //Válidamos y almcenamos el token
        $token = \s($_GET['token']);
        if(!$token) header('Location: /');
        $mostar = true;

        //Buscar el usuario por token
        $usuario = Usuario::where('token', $token);
        
        //Verificar el token
        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no válido');
            $mostar = false;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validarPassword();
            
            if(empty($alertas)){
                //Hashear Password
                $usuario->hashPassword();

                //Eliminamos el indice password2
                unset($usuario->password2);

                //Eliminar token
                $usuario->token = null;

                //Actualizamos cambios
                $resultado = $usuario->guardar();

                if($resultado){
                    header('Location: /');
                }
            }

        }

        //Obtener las alertas
        $alertas = Usuario::getAlertas();

        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer password',
            'alertas' => $alertas,
            'mostrar' => $mostar
        ]);
    }

    public static function mensaje(Router $router){
        
        $router->render('auth/mensaje', [
            'titulo' => 'Mensaje'
        ]);
    }

    public static function confirmar(Router $router){

        //Válidamos y almacenamos el token
        $token = \s($_GET['token']);
        if(!$token) header('Location: /');

        //Comprobar token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            //Establecemos una alerta
            Usuario::setAlerta('error', 'Token no válido');
        }else{
            //Enviamos una alerta al usuario
            Usuario::setAlerta('exito', 'Cuenta confirmada correctamente');
            //Eliminamos el indice del objeto password2
            unset($usuario->password2);
            //Cambiamos el valor en database columna token and confirmado
            $usuario->token = null;
            $usuario->confirmado = 1;
            //Actualizamos la database
            $usuario->guardar();
        }
        
        //Llamamos las alertas
        $alertas = Usuario::getAlertas();

        $router->render('auth/confirmar', [
            'titulo' => 'Confirmar cuenta',
            'alertas' => $alertas
        ]);
    }
}