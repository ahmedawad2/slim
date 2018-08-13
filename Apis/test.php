<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/test', function (Request $req, Response $res, array $args) {
    $data = ['internal_status' => 200, 'message' => 'it work\'s fine !'];
    return $res->withJson($data, 200);
});

$app->post('/test', function (Request $req, Response $res) {
    return $res->write('post request also works fine :D');
});