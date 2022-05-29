<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once __DIR__ . '/../models/ordersModel.php';
require_once __DIR__ . '/../service/userService.php';

$app->get('/orders', function (Request $request, Response $response) {
	$token = $request->getHeader('Authorization')[0];
	$conn = DB::connect();
	$userId = userService::getUserId($token);

	$query = pg_query($conn, ordersModel::getSql($userId));
	if(!$query)
		throw new Exception('Неправильный токен');

	$orders = pg_fetch_all($query);
	ordersModel::mapOrdersResponse($orders);

	// $response->getBody()->write(json_encode($order));
	// return $response
	// 	->withHeader('Content-Type', 'application/json');
});

$app->post('/orders', function (Request $request, Response $response) {
	$token = $request->getHeader('Authorization')[0];
	$body = json_decode($request->getBody());
	
	$conn = DB::connect();
	
	$userId = userService::getUserId($token);

	$addres = $body->addres;
	$orderSum = $body->orderSum;
	$orderCount = $body->orderCount;
	$orderDate = date("Y-m-d H:i:s");
	$orderItems = $body->orderItems;
	$orderItems = json_decode(json_encode($orderItems), true);

	$sizes = pg_query($conn, 'select * from public.sizes');
	$sizes = pg_fetch_all($sizes);
	$sizesDict = array_column($sizes, 'id','ru_size'); 

	$res = pg_query($conn, "INSERT INTO public.orders (order_date,sum,count,user_id,addres)
		VALUES ('$orderDate',$orderSum,$orderCount,$userId,'$addres')
		returning id");
	$res = pg_fetch_array($res);
	if(!boolval($res))
		throw new Exception('Не удалось создать заказ');
	
	$orderId = intval($res['id']);
	
	foreach($orderItems as $orderItem) {
		$orderItem = ['order_id' => $orderId,
			'shoe_id' => $orderItem['shoeId'],
			'size_id' => $sizesDict[$orderItem['ruSize']]
		];

		$res = pg_insert($conn,'public.order_items', $orderItem);
		if(!boolval($res))
			throw new Exception('Не удалось создать пункт заказа');
	}

	$response->getBody()->write(json_encode($orderId));
	return $response
		->withHeader('Content-Type', 'application/json');
});

?>

