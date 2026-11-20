<?php

require_once __DIR__ . '/../app/helpers/autoload.php';

$routes = require __DIR__ . '/../app/routes.php';

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
if ($scriptDir !== '/' && str_starts_with($requestUri, $scriptDir)){
    $requestUri = substr($requestUri, strlen($scriptDir));
}

$path = '/' . trim($requestUri, '/');

foreach ($routes as [$method, $routePath, $handler]) {
    if ($method === $requestMethod && $routePath === $path){
        [$controllerName, $actionName] = explode('@', $handler, 2);

        $controller = new $controllerName();
        $controller -> $actionName();

        exit;
    }
}

http_response_code(404);
$view404 = __DIR__ . '/../app/views/errors/404.php';
if (file_exists($view404)){
    require $view404;
} else {
    echo '404 Not Found';
}