<?php

namespace App\Actions;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class HelloAction
{
    public function __invoke(Request $request, Response $response, string $name)
    {
        $name = ucfirst($name);

        $response->getBody()->write("Hello {$name}!");

        return $response;
    }
}
