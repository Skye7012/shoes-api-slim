<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/orders', function (Request $request, Response $response) {
	$token = $request->getHeader('Authorization')[0];
	$sql = "SELECT id FROM users WHERE login = '$token'";
	$conn = DB::connect();
	$query = pg_query($conn, $sql);
	if(!$query)
		throw new Exception('Неправильный токен');

	$user = pg_fetch_assoc($query);

	// $response->getBody()->write(json_encode($order));
	// return $response
	// 	->withHeader('Content-Type', 'application/json');
});

$app->post('/orders', function (Request $request, Response $response) {
	$token = $request->getHeader('Authorization')[0];
	$body = json_decode($request->getBody());

	$sql = "SELECT id FROM users WHERE login = '$token'";
	$conn = DB::connect();
	$query = pg_query($conn, $sql);
	if(!$query)
		throw new Exception('Неправильный токен');

	$user = pg_fetch_assoc($query);
	$userId = $user['id'];

	$addres = $body->addres;
	$orderSum = $body->orderSum;
	$orderCount = $body->orderCount;
	$orderDate = date("Y-m-d H:i:s");
	$orderItems = $body->orderItems;

	$sizes = pg_query($conn, 'select * from public.sizes');
	$sizes = pg_fetch_all($sizes);
	// var_dump($sizes);
	// $newSizes = array();
	// $mapSize = function($size) {
	// 	$newSizes[$size['id']] = $size['ru_size'];
	// };
	// array_map($mapSize, $sizes);
	// $size = array_search(['ru_size' => 40], $sizes);
	// var_dump($newSizes);
	$ns = array();

	array_walk($sizes, function ($item, $key) {
		$key = $item['ru_size'];
		$item = $item['id'];
		$ns[$key] = $item;
		var_dump($ns);
	});

	var_dump($ns);

	// $res = pg_insert($conn,'public.orders', ['order_id']);
	// $res = boolval($res);

	// foreach($orderItems as $orderItem) {
	// 	$res = pg_insert($conn,'public.order_items', ['order_id']);
	// 	$res = boolval($res);
	// }

	// $order = ['login' => $login,
	// 	'password' => hash('SHA512', $password),
	// 	'name' => $name,
	// 	'fname' => $fname,
	// 	'phone' => $phone
	// ];

	// $conn = DB::connect();
	// $res = pg_insert($conn,'public.orders', $order);
	// $res = boolval($res);

	// $response->getBody()->write(json_encode($res));
	// return $response
	// 	->withHeader('Content-Type', 'application/json');
});

?>

