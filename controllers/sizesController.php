<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/sizes', function (Request $request, Response $response) {
	$sql = "SELECT ru_size FROM sizes";
	$conn = DB::connect();
	$query = pg_query($conn, $sql);
	$sizes = pg_fetch_all_columns($query,0);

	$response->getBody()->write(json_encode($sizes));
	return $response
		->withHeader('Content-Type', 'application/json');
});