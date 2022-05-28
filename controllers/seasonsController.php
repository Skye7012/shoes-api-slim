<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/seasons', function (Request $request, Response $response) {
	$sql = "SELECT * FROM seasons";
	$conn = DB::connect();
	$query = pg_query($conn, $sql);
	$seasons = pg_fetch_all($query);
	$res = ['items' => $seasons, 'totalCount' => count($seasons)];

	$response->getBody()->write(json_encode($res));
	return $response
		->withHeader('Content-Type', 'application/json');
});