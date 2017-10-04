<?php
require_once "core/config.php";
require_once "core/view.php";
require_once "core/connection.php";

$routes = explode('/', $_SERVER['REQUEST_URI']);

$controller_name = "Main";
$action_name = 'index';
$params = null;

// получаем контроллер
if (!empty($routes[1])) {
    $controller_name = $routes[1];
}

// получаем действие
if (!empty($routes[2])) {
    $action_name = $routes[2];
}

//фиксируем наличие параметра
if (!empty($routes[3])) {
    $params = array_slice($routes, 3);
}

$filename = "controllers/".strtolower($controller_name).".controller.php";

try {
    if (file_exists($filename)) {
        require_once $filename;
    } else {
        throw new Exception("File not found");
    }

    $classname = '\App\\'.ucfirst($controller_name);

    if (class_exists($classname)) {
        $controller = new $classname();
    } else {
        throw new Exception("File found but class not found");
    }

    if (method_exists($controller, $action_name)) {
        if ($params) {
            $controller->$action_name($params);
        } else {
            $controller->$action_name();
        }        
    } else {
        throw new Exception("Method not found");
    }
} catch (Exception $e) {
    require_once "errors/404.php";
}
?>


