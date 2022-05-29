<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once __DIR__ . '/../models/shoesModel.php';

$app->get('/shoes', function (Request $request, Response $response) {
	$conn = DB::connect();

	$params = $request->getQueryParams();
	$sql = shoesModel::getSelectQuery($params);
	
	$query = pg_query($conn, $sql);
	$countQuery = pg_query($conn, shoesModel::getCountQuery());
	$shoes = pg_fetch_all($query);

	$count = pg_num_rows($countQuery);
	$shoes = shoesModel::mapShoesResponse($shoes);
	$res = ['totalCount' => $count, 'items' => $shoes];

	$response->getBody()->write(json_encode($res));
	return $response
		->withHeader('Content-Type', 'application/json');
});