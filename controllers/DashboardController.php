<?php

namespace Controllers;

use MVC\Router;
use Model\Usuario;
use Model\Proyecto;

class DashboardController{

    public static function index(Router $router){

        session_start();
        \isAuth();

        //Obtener el id del usuario
        $id = $_SESSION['id'];
        $proyectos = Proyecto::belongsTo('propetarioId', $id);
        

        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }

    public static function crear_Proyecto(Router $router){
        session_start();
        \isAuth();

        //Creamos la instancia
        $proyecto = new Proyecto;

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //sincronizar $_POST
            $proyecto->sincronizar($_POST);

            //Validamos formulario
            $alertas = $proyecto->validarProyecto();

            if(empty($alertas)){
                //Generar hash a la url
                $hash = md5(uniqid());
                $proyecto->url = $hash;

                //Almacenar al usuario del proyecto
                $usuario = $_SESSION['id'];
                $proyecto->propetarioId = $usuario;

                //Crear Proyecto
                $proyecto->guardar();

                //Redireccionar al usuario
                header('Location: /proyecto?id=' . $proyecto->url);
            }
        }

        $router->render('dashboard/crear_Proyecto', [
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }

    public static function proyecto( Router $router){

        session_start();
        \isAuth();

        //Revisar que quién visita es quién lo creo.
        $token = $_GET['id'];
        if(!$token) header('Location: /');

        $proyecto = Proyecto::where('url', $token);

        if($proyecto->propetarioId !== $_SESSION['id']){
            header('Location: /');
        }


        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto
        ]);
    }

    public static function perfil(Router $router){
        session_start();
        \isAuth();

        $alertas = [];

        $usuario = Usuario::find($_SESSION['id']);


        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $usuario->sincronizar($_POST);
            
            $alertas = $usuario->validarUsuarioPerfil();

            if (empty($alertas)) {
                
                
                //Buscar email registrados
                $existeUsuario = Usuario::where('email', $usuario->email);
                
                //Verificar email y usuario por id
                if ($existeUsuario && $existeUsuario->id !== $usuario->id ) {
                    Usuario::setAlerta('error', 'Email no válido, ya se encuentra registrada');
                    $alertas = Usuario::getAlertas();
                } else{
                    //Guardar cambios
                    $usuario->guardar();
    
                    //Actualizar la sesión
                    $_SESSION['nombre'] = $usuario->nombre;
    
                    //Alerta
                    Usuario::setAlerta('exito', 'Guardado Correctamente');
                    $alertas = Usuario::getAlertas();
                    
                }
            }
            //\debuguear($usuario);
        }

        


        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'alertas' => $alertas,
            'usuario' => $usuario
        ]);
    }

    public static function cambiar_Password(Router $router){

        session_start();
        \isAuth();
        
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $usuario = Usuario::find($_SESSION['id']);

            $usuario->sincronizar($_POST);

            $alertas = $usuario->nuevoPassword();

            if (empty($alertas)) {
                
                $resultado = $usuario->comprobarPassword();

                if ($resultado) {

                    //Actualizar propiedad password
                    $usuario->password = $usuario->passwordNuevo;

                    //Eliminar propiedades
                    unset($usuario->passwordActual);
                    unset($usuario->passwordNuevo);

                    //HasshPassword 123456789
                    $usuario->hashPassword();

                    $resultado = $usuario->guardar();

                    if ($resultado) {
                        Usuario::setAlerta('exito', 'El password ha sido actualizado correctamente');
                        $alertas = $usuario->getAlertas();
                    }
                }else{
                    Usuario::setAlerta('error', 'Password incorrecto');
                    $alertas = $usuario->getAlertas();
                }
            }
        }

        $router->render('dashboard/cambiar_Password', [
            'titulo' => 'Cambiar Password',
            'alertas' => $alertas
        ]);
    }
}