<?php

namespace Controllers;
use Model\Tareas;
use Model\Proyecto;

class TareasController{
    
    public static function index(){
        
        //Proteger la url
        $url = $_GET['id'];
        if (!$url) {
            header('Location: /dashboard');
        }

        //Traer la tabla de la DB
        $proyecto = Proyecto::where('url', $url);

        //Iniciar sesión
        session_start();

        //Preguntar si el proyecto existe o si el usuario es quién inicio sesión
        if (!$proyecto || $proyecto->propietarioId === $_SESSION['id']) {
            header('Location: /404');
        }

        $tareas = Tareas::belongsTo('proyectoId', $proyecto->id);
        
        //Enviar la respuesta tipo json a javaScrip en arreglo asociativo
        echo json_encode(['tareas' => $tareas]);
    }

    public static function crear(){

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            session_start();

            $proyectoId = $_POST['proyectoId'];

            $proyecto = Proyecto::where('url', $proyectoId);

            $propetarioId =  $proyecto->propetarioId;
            
            if(!$proyecto || $propetarioId !== $_SESSION['id']){
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al añadir tarea'
                ];
                echo json_encode($respuesta);

                return;
            }

            //Todo bien, instanciar y crear la tarea
            $tarea = new Tareas($_POST);
            //solucionar el error en el query
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();
            $respuesta = [
                'tipo' => 'exito',
                'mensaje' => 'La tarea fue agregada correctamente',
                'id' => $resultado['id'],
                'proyectoId' => $proyecto->id
            ];
            //Enviamos la respuesta por medio de json_encode();
            echo json_encode($respuesta);
            /*
            }else{
                $respuesta = [
                    'tipo' => 'exito',
                    'mensaje' => 'La tarea fue agregada correctamente'
                ];
                echo json_encode($respuesta);
            }
            */
        }
    }

    public static function actualizar(){

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //Iniciar sesión
            session_start();

            //Tomar y almacenar la url del POST
            $proyectoId = $_POST['proyectoId'];
            
            //Buscar el proyecto
            $proyecto = Proyecto::where('url', $proyectoId);

            //Modificar el la variable a comparar con el id
            $proyectoId = $proyecto->propetarioId;

            //Comprobar sí el proyecto existe o sí el usuario es el mismo.
            if(!$proyecto || $proyectoId !== $_SESSION['id']){
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al cambiar el estado de la tarea'
                ];

                echo json_encode($respuesta);
                return;

            }

            //Sincronizar POST
            $tarea = new Tareas($_POST);

            //Cambiamos el valor por el id.
            $tarea->proyectoId = $proyecto->id;

            //Guardar cabmios al servidor
            $resultado = $tarea->guardar();
            if($resultado) {
                $respuesta = [
                    'tipo' => 'exito',
                    'id' => $tarea->id,
                    'proyectoId' => $proyecto->id,
                    'mensaje' => 'Actualizado correctamente'
                ];

                //echo json_encode($respuesta);
                echo json_encode(['respuesta' => $respuesta]);
            }
        }
    }

    public static function eliminar(){

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //Iniciar sesión
            session_start();

            //Tomar y almacenar la url del POST
            $proyectoId = $_POST['proyectoId'];
            
            //Buscar el proyecto
            $proyecto = Proyecto::where('url', $proyectoId);

            //Modificar el la variable a comparar con el id
            $proyectoId = $proyecto->propetarioId;

            //Comprobar sí el proyecto existe o sí el usuario es el mismo.
            if(!$proyecto || $proyectoId !== $_SESSION['id']){
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al realizar los cambios'
                ];

                echo json_encode($respuesta);
                return;

            }

            $tarea = new Tareas($_POST);

            $tarea->proyectoId = $proyecto->id;

            //Selecionar la tarea a eliminar
            $resultado = $tarea->eliminar();
            
            if ($resultado) {
                $respuesta = [
                    'tipo' => 'exito', 
                    'mensaje' => 'Eliminado correctamente',
                    'id' => $tarea->id
                ];
                echo json_encode($respuesta);
            }
        }
    }
}