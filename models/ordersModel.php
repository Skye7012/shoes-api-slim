<?php

class ordersModel {
	public static function getSql($userId = "") {
	return
"SELECT o.id, o.order_date, o.sum, o.count, o.addres,
	oi.id AS order_item_id, s.ru_size,
	sh.id AS shoe_id, sh.name AS shoe_name, sh.image AS shoe_image, sh.price AS shoe_price
FROM public.orders o
	JOIN public.order_items oi ON oi.order_id = o.id 
	JOIN public.sizes s ON s.id = oi.size_id 
	JOIN public.shoes sh ON sh.id = oi.shoe_id 
WHERE o.user_id = $userId
";
	}

	public static function mapOrdersResponse($orders) {
		$items = array();

		foreach ($orders as $order) {
			$orderId = $order['id'];
			$items[$orderId]['id'] = $orderId;
			$items[$orderId]['orderDate'] = $order['order_date'];
			$items[$orderId]['addres'] = $order['addres'];
			$items[$orderId]['sum'] = $order['sum'];
			$items[$orderId]['count'] = $order['count'];

			$orderItemId = $order['order_item_id'];
			$items[$orderId]['orderItems'][$orderItemId]['id'] = $orderItemId;
			$items[$orderId]['orderItems'][$orderItemId]['ruSize'] = $order['ru_size'];

			$shoeId = $order['shoe_id'];
			$items[$orderId]['orderItems'][$orderItemId]['shoe'][$shoeId]['id'] = $shoeId;
			$items[$orderId]['orderItems'][$orderItemId]['shoe'][$shoeId]['name'] = $order['shoe_name'];
			$items[$orderId]['orderItems'][$orderItemId]['shoe'][$shoeId]['image'] = $order['shoe_image'];
			$items[$orderId]['orderItems'][$orderItemId]['shoe'][$shoeId]['price'] = $order['shoe_price'];
		}

		var_dump($items);
	}
}

?>