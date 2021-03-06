<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/test', function (Request $req, Response $res, array $args) {
    $data = ['internal_status' => 200, 'message' => 'it work\'s fine !'];
    return $res->withJson($data, 200);
});

$app->get('/test/placeholder/{placeholder}', function (Request $req, Response $res) {
    return 'you entered: ' . $req->getAttribute('placeholder');
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
$app->get('/test/view', function (Request $req, Response $res) {
    $response = $this->view->render($res, 'index.phtml', ['name' => 'ahmed awad']);
    return $response;
});

$app->post('/test/view/custom', function (Request $req, Response $res) {
    $data['body'] = $req->getParsedBody();
    $data['router'] = $this->router;
    return $this->view->render($res, 'custom.phtml', $data);
});

//this route will work with the default http post verb,
//and will also work with a Get request only when adding X-Http-Method-Override header with value: POST
$app->post('/test/overrideHttpMethod', function (Request $req, Response $res) {
    return $res->write('you have use a get request but it was overriden using the X-Http-Method-Override header to mimic a post request!');
});

// testing implementing a route that works with more than one HTTP verb
$app->map(['get', 'delete'], '/test/multiMethods', function (Request $req, Response $res) {
    $message = 'this route is working with either GET and DELETE';
    if ($req->isGet()) {
        $message .= ' and you have used GET this time';
    } elseif ($req->isDelete()) {
        $message .= ' and you have used DELETE this time';
    }

    return $res->getBody()->write($message);
});

//adding single optional parameter
$app->get('/test/optional[/{id}]', function (Request $req, Response $res, array $args) {
    $id = $args['id'] ? $args['id'] : 'null';
    return $res->getBody()->write('you have sent ' . $id . ' as an optional parameter');
});

//adding 2 optional parameters
//BEWARE of adding the whole optional pattern between [] including the /, and each param is surrounded by [] then {}
$app->get('/test/optional2[/{fname}[/{lname}]]', function (Request $req, Response $res, array $args) {
    $fname = $args['fname'];
    $lname = $args['lname'];
    $message = 'you have sent ';
    if ($fname) {
        $message .= "fname = $fname ";
        if ($lname) {
            $message .= " and lname = $lname";
        } else {
            $message .= " and lname = null";
        }
    } else {
        $message .= 'nothing';
    }
    return $res->getBody()->write($message);
});

//adding unlimited number or params to URL segment
$app->get('/test/unlimitedOptional[/{manyParams:.*}]', function (Request $req, Response $res) {
    $optionalParams = $req->getAttribute('manyParams');
    $optionalParams = $optionalParams ? explode('/', $optionalParams) : [];
    return $res->getBody()->write('you have sent ' . json_encode($optionalParams));
});

//adding optional single parameter matching a Regex
$app->get('/test/regex[/{id:[0-9]+}]', function (Request $req, Response $res) {
    $id = $req->getAttribute('id');
    $id = $id ? $id : 'null';
    return $res->getBody()->write("you have sent $id");
});


//adding nested group of routes
$app->group('/api', function () {
    $this->group('/v1', function () {
        $this->get('/fromV1', function (Request $req, Response $res) {
            return $res->getBody()->write('you are in api/v1');
        });
    });
    $this->group('/v2', function () {
        $this->get('/fromV2', function (Request $req, Response $res) {
            return $res->getBody()->write('you are in api/v2');
        });
    });
});
