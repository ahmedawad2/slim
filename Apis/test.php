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

$app->get('/parse/post/{id}', function (Request $req, Response $res, $args) {
    $parse = new \Parse\ParseQuery('Post');
    $post = $parse->first($args['id']);
    return $res->write($post->get('body'));
});

$app->get('/test/headers', function (Request $req, Response $res) {
    print_r($req->getHeaders());
    print_r($res->getHeaders());
    print_r($_SERVER);
});

$app->get('/test/debInjection', function (Request $req, Response $res) {
    //calling a method from the injected class
    print_r($this->debInjectionTest->getPrivate());
});


$app->get('/test/logging', function(){
    //use the injected Monolog class to add a record to the log file
    print_r($this->logger->addRecord(($this->logger)::NOTICE, 'message', ['a' => 'a']));
});

