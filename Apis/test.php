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


$app->get('/test/logging', function () {
    //use the injected Monolog class to add a record to the log file
    print_r($this->logger->addRecord(($this->logger)::NOTICE, 'message', ['a' => 'a']));
});

$app->get('/test/get_query_params/{placeholder}', function (Request $req, Response $res, $args) {
    $data = [];
    $data['placeholder'] = $args['placeholder'];
    $data['query_params'] = $req->getQueryParams();
    $data['query_param'] = $req->getQueryParam('query_param');
    $data['another_way'] = $req->getAttribute('placeholder');
    print_r($data);
});

$app->post('/test/post_params/{placeholder}', function (Request $req, Response $res) {
    //test using this URL:
    //http://localhost/slim/Apis/test/post_params/placeholder_val?query_param=query_param_value&query_param2=query_value2
    $data = [];
    $data['body_params'] = $req->getParsedBody();
    $data['body_param'] = $req->getParsedBodyParam('body_param');
    $data['placeholder'] = $req->getAttribute('placeholder');
    $data['query_params'] = $req->getQueryParams();
//    $res->withHeader('Content-Type', 'aplication/json');
//    $res->withHeader('Last-Modified', time());
    return $res->write(json_encode($data))->withHeader('Content-Type', 'application/json')->withHeader('Last-Modified', time());
});

//testing rendering a view with PhpRenderer
$app->get('/test/view', function(Request $req, Response $res){
    $response = $this->view->render($res, 'index.phtml', ['name' => 'ahmed awad']);
    return $response;
});

