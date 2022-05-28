<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/destinations', function (Request $request, Response $response) {
	$sql = "SELECT * FROM destinations";
	$conn = DB::connect();
	$query = pg_query($conn, $sql);
	$destinations = pg_fetch_all($query);
	$res = ['items' => $destinations, 'totalCount' => count($destinations)];

	$response->getBody()->write(json_encode($res));
	return $response
		->withHeader('Content-Type', 'application/json');
});