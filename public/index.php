<?php 

require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\LoginController;
use Controllers\TareasController;
use Controllers\DashboardController;


$router = new Router();

//Login
$router->get('/', [LoginController::class, 'login']);
$router->post('/', [LoginController::class, 'login']);
$router->get('/logout', [LoginController::class, 'logout']);

//Crear cuenta
$router->get('/crear', [LoginController::class, 'crear']);
$router->post('/crear', [LoginController::class, 'crear']);

//Olvidó password
$router->get('/olvido', [LoginController::class, 'olvido']);
$router->post('/olvido', [LoginController::class, 'olvido']);

//Reestablecer la cuenta
$router->get('/reestablecer', [LoginController::class, 'reestablecer']);
$router->post('/reestablecer', [LoginController::class, 'reestablecer']);

//Confirmar cuenta
$router->get('/mensaje', [LoginController::class, 'mensaje']);
$router->get('/confirmar', [LoginController::class, 'confirmar']);

//Dashboard
$router->get('/dashboard', [DashboardController::class, 'index']);
$router->get('/crear-Proyecto', [DashboardController::class, 'crear_Proyecto']);
$router->post('/crear-Proyecto', [DashboardController::class, 'crear_Proyecto']);
$router->get('/proyecto', [DashboardController::class, 'proyecto']);
$router->get('/perfil', [DashboardController::class, 'perfil']);
$router->post('/perfil', [DashboardController::class, 'perfil']);
$router->get('/cambiar-password', [DashboardController::class, 'cambiar_Password']);
$router->post('/cambiar-password', [DashboardController::class, 'cambiar_Password']);

//API para las tareas
$router->get('/api/tareas', [TareasController::class, 'index']);
$router->post('/api/tarea', [TareasController::class, 'crear']);
$router->post('/api/tarea/actualizar', [TareasController::class, 'actualizar']);
$router->post('/api/tarea/eliminar', [TareasController::class, 'eliminar']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();