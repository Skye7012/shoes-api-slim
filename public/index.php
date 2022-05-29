<?php
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/db.php';

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
require_once __DIR__ . '/../controllers/brandsController.php';

//seasonsController
require_once __DIR__ . '/../controllers/seasonsController.php';

//destinationsController
require_once __DIR__ . '/../controllers/destinationsController.php';

//sizesController
require_once __DIR__ . '/../controllers/sizesController.php';

//shoesController
require_once __DIR__ . '/../controllers/shoesController.php';

//usersController
require_once __DIR__ . '/../controllers/usersController.php';

//ordersController
require_once __DIR__ . '/../controllers/ordersController.php';

$app->run();

?>