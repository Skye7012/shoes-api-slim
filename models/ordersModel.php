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
			$items[$order['id']][] = $order;
		}

		var_dump($items);
	}
}

?>