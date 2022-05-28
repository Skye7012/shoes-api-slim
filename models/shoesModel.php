<?php

class shoesModel {
	private static $selectQuery =
"SELECT sh.id, sh.name, sh.image, sh.price, 
	b.id as brandId, b.name as brandName,
	d.id as destinationId, d.name as destinationName,
	s.id as seasonId, s.name as seasonName
FROM public.shoes sh
	JOIN public.brands b on sh.brand_id = b.id  
	JOIN public.destinations d on sh.destination_id  = d.id
	JOIN public.seasons s on sh.season_id  = s.id";

	public static function getSelectQuery($params) {
		$page = $params['Page'];
		$limit = $params['Limit'];
		$offset = (intval($page) - 1) * intval($limit);
		$offset = "OFFSET $offset";
		$limit = "LIMIT $limit";
		$selectQuery = self::$selectQuery;
		
		return 
"$selectQuery
$limit
$offset";
	}

	public static function mapShoesResponse($shoes) {
		$res = array();

		foreach ($shoes as $shoe) {
			$shoe = (object)$shoe;

			$resItem = ['id' => $shoe->id,
				'name' => $shoe->name,
				'image' => $shoe->image,
				'price' => $shoe->price,
				'brand' => ['id' => $shoe->brandid, 'name' => $shoe->brandname],
				'season' => ['id' => $shoe->seasonid, 'name' => $shoe->seasonname],
				'destination' => ['id' => $shoe->destinationid, 'name' => $shoe->destinationname],
			];

			array_push($res, $resItem);
		}

		return $res;
	}
}

?>