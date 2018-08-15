<?php

use Interop\Container\ContainerInterface;
require_once '../Middlewares/demo.php';

class Demo
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    public function ind($req, $res, $arsg)
    {
        $this->container->logger->info('hello');
        return 'working!';
    }

    public function testRouteName($req, $res, $args)
    {
        //i will use the pathFor() in the $router to refer to this route
        return 'after using pathfor(), you called this route!';
    }

    public function middleware($req, $res)
    {
        return $res->getBody()->write('the function visited also !');
    }
}

//related routes
//NOTE: you can name any route you define, which is GREAT!
$app->get('/demo/ind', \Demo::class . ':ind')->setName('demoIndex');
$app->get('/demo/testRouteName', \Demo::class . ':testRouteName')->setName('testRouteName');
$app->get('/demo/middleware', \Demo::class . ':middleware')->setName('middlewareTes')->add(new DemoMiddleware1())->add(new DemoMiddleware2());
