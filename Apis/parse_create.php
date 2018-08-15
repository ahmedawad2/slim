<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->post('/parse/post', function (Request $req, Response $res, $args){
    $contentType = $res->getHeaders();
    print_r($_SERVER);
});