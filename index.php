<?php

require_once 'Database.php';
require_once 'src/controllers/AppController.php';
require_once 'src/controllers/SecurityController.php';

$routing = [
    'login' => [
        'controller' => 'SecurityController',
        'action' => 'login',
        'access' => []
    ],
];

$controller = new AppController();

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url( $path, PHP_URL_PATH);
$action = explode("/", $path)[0];
$action = $action == null ? 'login': $action;

switch($action){
    case "login":
        //TODO check if user is authenticated and has access to system
        $controllerName = $routing[$action]['controller'];
        $actionName = $routing[$action]['action'];
        $controller = new $controllerName;
        $controller->$actionName();
        break;
    default:
        $controller->render($action);
        break;
}