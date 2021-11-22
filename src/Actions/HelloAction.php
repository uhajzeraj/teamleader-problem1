<?php

declare(strict_types=1);

namespace App\Actions;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class HelloAction
{
    public function __invoke(Request $request, Response $response, string $name): Response
    {
        $name = ucfirst($name);

        $response->getBody()->write("Hello {$name}!");

        return $response;
    }
}
