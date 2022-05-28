<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/db.php';

$app = AppFactory::create();

$app->get('/hello/{name}', function (Request $request, Response $response, $args) {
	$name = $args['name'];
	$response->getBody()->write("Hello $name!");
	return $response;
});

//Brand controller
require __DIR__ . '/../controllers/brandsController.php';

$app->run();