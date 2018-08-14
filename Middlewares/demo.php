<?php

class DemoMiddleware1
{
    public function __invoke($req, $res, $next)
    {
        $res->getBody()->write('middleware1 invoked! ');
        return $next($req, $res);
    }
}

class DemoMiddleware2
{
    public function __invoke($req, $res, $next)
    {
        $res->getBody()->write('middleware2 invoked! ');
        return $next($req, $res);
    }
}