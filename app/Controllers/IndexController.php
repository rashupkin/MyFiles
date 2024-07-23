<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Response;

class IndexController extends BaseController
{
    public function index(): Response
    {
        return $this->successViewResponse("index.html", 200);
    }
}
