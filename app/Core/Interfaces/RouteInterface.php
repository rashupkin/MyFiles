<?php

namespace App\Core\Interfaces;

interface RouteInterface
{
    const HTTP_METHOD = "HTTP_METHOD";
    const CONTROLLER_METHOD = "CONTROLLER_METHOD";
    const CONTROLLER_NAME = "CONTROLLER_NAME";
    const PARAMS = "PARAMS";
    const ALLOWED_ROLES = "ALLOWED_ROLES";
    const ROUTE_INFO = "routeInfo";
    const HAS_ROUTE = "hasRoute";
}
