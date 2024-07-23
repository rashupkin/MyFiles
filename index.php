<?php

use App\Core\App;
use App\Core\Request;
use App\Core\Router;
use App\Core\Routes;
use App\Core\SessionManager;

require_once "vendor/autoload.php"; // import autoload
require_once "env.php"; // import config file

// session start
SessionManager::getInstance();

// kernel services and repositories
App::addServices();
App::addRepositories();

// kernel router
App::add(Routes::class, []);
App::add(Router::class, [App::getService(Routes::class)]);

$request = new Request(
    $_SERVER["REQUEST_URI"],
    $_SERVER["REQUEST_METHOD"]
);
$router = App::getService(Router::class);

$response = $router->processRequest($request);

// get response data
$statusInfo = $response->getStatusInfo();
$statusCode = $statusInfo["code"];
$statusMessage = $statusInfo["msg"] ?? "Not Found";
$headers = $response->getHeaders();
$data = $response->getData();

// proccessing response from controller or router
if (preg_match("/^4|5/", $statusCode)) {
    http_response_code($statusCode);

    require_once "app/Templates/404.template.php";
} else if (preg_match("/^2/", $statusCode)) {
    foreach ($headers as $header) {
        header($header);
    }

    http_response_code($statusCode);

    if (is_string($data)) {
        echo $data;
    } else if (isset($data["view"])) {
        require_once $_ENV["DIR"] . "/" . $data["view"];
    }
}
