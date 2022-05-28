<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/brands', function (Request $request, Response $response) {
	$sql = "SELECT * FROM brands";
	$conn = DB::connect();
	$query = pg_query($conn, $sql);
	$brands = pg_fetch_all($query);
	$res = ['items' => $brands, 'totalCount' => count($brands)];

	$response->getBody()->write(json_encode($res));
	return $response
		->withHeader('Content-Type', 'application/json');
});

$app->post('/brands', function (Request $request, Response $response) {
	$body = json_decode($request->getBody());
	$name = $body->name;

	$conn = DB::connect();
	$res = pg_insert($conn,'public.brands',['name' => $name]);
	$res = boolval($res);

	$response->getBody()->write(json_encode($res));
	return $response
		->withHeader('Content-Type', 'application/json');
});

$app->put('/brands/{id}', function (Request $request, Response $response, $args) {
	$body = json_decode($request->getBody());
	$name = $body->name;

	$conn = DB::connect();
	$res = pg_update($conn,'public.brands',['name' => $name], ['id' => $args['id']]);
	$res = boolval($res);

	$response->getBody()->write(json_encode($res));
	return $response
		->withHeader('Content-Type', 'application/json');
});

$app->delete('/brands/{id}', function (Request $request, Response $response, $args) {
	$conn = DB::connect();
	$res = pg_delete($conn,'public.brands', ['id' => $args['id']]);
	$res = boolval($res);

	$response->getBody()->write(json_encode($res));
	return $response
		->withHeader('Content-Type', 'application/json');
});