<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once __DIR__ . '/../models/shoesModel.php';

$app->get('/shoes', function (Request $request, Response $response) {
	$sql = shoesModel::getSelectQuery();
	$conn = DB::connect();
	$query = pg_query($conn, $sql);
	$shoes = pg_fetch_all($query);
	$res = shoesModel::mapShoesResponse($shoes);
	//$res = ['items' => $shoes, 'totalCount' => count($shoes)];

	$response->getBody()->write(json_encode($res));
	return $response
		->withHeader('Content-Type', 'application/json');
});