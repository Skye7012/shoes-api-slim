<?php
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/db.php';

$app = AppFactory::create();

$app->options('/{routes:.+}', function ($request, $response, $args) {
	return $response;
});

$app->add(function ($request, $handler) {
	$response = $handler->handle($request);
	return $response
			->withHeader('Access-Control-Allow-Origin', 'http://localhost:8080')
			->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
			->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

//brandsController
require __DIR__ . '/../controllers/brandsController.php';

//seasonsController
require __DIR__ . '/../controllers/seasonsController.php';

//destinationsController
require __DIR__ . '/../controllers/destinationsController.php';

//sizesController
require __DIR__ . '/../controllers/sizesController.php';

//shoesController
require __DIR__ . '/../controllers/shoesController.php';

//usersController
require __DIR__ . '/../controllers/usersController.php';

//ordersController
require __DIR__ . '/../controllers/ordersController.php';

$app->run();

?>