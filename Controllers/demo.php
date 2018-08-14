<?php

use Interop\Container\ContainerInterface;

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
}

//routes
$app->get('/demo/ind', \Demo::class . ':ind');
