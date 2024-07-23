<?php

namespace App\Core;

use App\Core\Interfaces\IService;
use App\Core\Security\Security;
use App\Core\Service\ResponseService;
use App\Core\Interfaces\RouteInterface;

class Router implements IService, RouteInterface
{
    private array $routesList;

    public function __construct(Routes $routes)
    {
        $this->routesList = $routes->get();
    }

    public function processRequest(Request $request): Response
    {
        $routeInfo = $this->getRouteInfo($request);

        if (!$routeInfo[self::HAS_ROUTE]) {
            return App::getService(ResponseService::class)->getCode(404);
        }

        $security = new Security();
        $response = $security->accessUser($routeInfo[self::ROUTE_INFO][self::ALLOWED_ROLES]);

        if ($response->getStatusInfo()["code"] === 403 || $response->getStatusInfo()["code"] === 500) {
            return $response;
        }

        $controllerName = $routeInfo[self::ROUTE_INFO][self::CONTROLLER_NAME];
        $controllerMethod = $routeInfo[self::ROUTE_INFO][self::CONTROLLER_METHOD];
        $params = $routeInfo[self::ROUTE_INFO][self::PARAMS] ?? null;

        // get parameters in request
        $routes = explode("/", $request->getRoute());

        if (isset($params)) {
            $params = array_slice($routes, count($routes) - count($params));
        }

        $controllersFolders = $_ENV["CONTROLLERS_DIRECTORIES"];

        foreach ($controllersFolders as $controllerFolder) {
            if (class_exists($_ENV["MAIN_FOLDER"] . "\\" . str_replace("/", "\\", $controllerFolder) . "\\" . $controllerName)) {
                $controllerName = $_ENV["MAIN_FOLDER"] . "\\" . str_replace("/", "\\", $controllerFolder) . "\\" . $controllerName;
                break;
            }
        }

        // create controller
        $controller = new $controllerName;

        if (!method_exists($controller, $controllerMethod)) {
            return App::getService(ResponseService::class)->getCode(404);
        }

        // call controller action
        if (count($request->getData()) !== 0 && isset($params)) {
            $result = $controller->$controllerMethod($request->getData(), ...$params);
        } else if (isset($params)) {
            $result = $controller->$controllerMethod(...$params);
        } else if (count($request->getData()) !== 0) {
            $result = $controller->$controllerMethod($request->getData());
        } else {
            $result = $controller->$controllerMethod();
        }

        return $result;
    }

    private function getRouteInfo(Request $request): array
    {
        foreach ($this->routesList as $routeKey => $routeArray) {
            if (preg_match($routeKey, $request->getRoute()) && $request->getMethod() === $routeArray[self::HTTP_METHOD]) {
                return [self::HAS_ROUTE => true, self::ROUTE_INFO => $routeArray];
            }
        }

        return [self::HAS_ROUTE => false];
    }
}
