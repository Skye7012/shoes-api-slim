<?php

class shoesModel {
	private static function getSql($filter = "", $sort = "", $pagination = "") {
	return
"SELECT sh.id, sh.name, sh.image, sh.price, 
	b.id as brandId, b.name as brandName,
	d.id as destinationId, d.name as destinationName,
	s.id as seasonId, s.name as seasonName,
	array_to_json(array_agg(sz.ru_size)) AS ru_sizes
FROM public.shoes sh
	JOIN public.brands b on sh.brand_id = b.id  
	JOIN public.destinations d on sh.destination_id  = d.id
	JOIN public.seasons s on sh.season_id  = s.id
	JOIN public.shoes_sizes ss ON sh.id = ss.shoes_id 
	JOIN public.sizes sz ON sz.id = ss.sizes_id 
$filter
GROUP BY sh.id, b.id, d.id, s.id
$sort
$pagination
";
	}

	public static function getSelectQuery($params) {
		$filter = self::getFilterSql($params);
		$sort = self::getSortSql($params);
		$pagination = self::getPaginationSql($params);
		return self::getSql($filter, $sort, $pagination);
	}

	public static function getCountQuery($params) {
		$filter = self::getFilterSql($params);
		return self::getSql($filter);
	}

	public static function getSelectByIdsQuery($params) {
		$ids = $params['ids'];
		$filter = "WHERE FALSE";
		if($ids)
			$filter = "WHERE sh.id IN (". implode(',',$ids) .")";
		return self::getSql($filter);
	}

	private static function getFilterSql($params) {
		$brandFilters = $params['BrandFilters'];
		$brandFilters = self::getInSql($brandFilters);
		$destinationFilters = $params['DestinationFilters'];
		$destinationFilters = self::getInSql($destinationFilters);
		$seasonFilters = $params['SeasonFilters'];
		$seasonFilters = self::getInSql($seasonFilters);
		$sizeFilters = $params['SizeFilters'];
		$sizeFilters = self::getInSql($sizeFilters);

		$searchQuery = $params['SearchQuery'];
		$searchQuery = self::getSearchSql($searchQuery);

		$res = 
"WHERE b.id $brandFilters
	AND d.id $destinationFilters
	AND s.id $seasonFilters
	AND sz.ru_size $sizeFilters
	AND $searchQuery
";
		return $res;
	}

	private static function getInSql($param) {
		if($param)
			return "IN (" . implode(',', $param) . ")";
		else
			return "IS NOT NULL";
	}

	private static function getSearchSql($searchQuery) {
		if($searchQuery)
			return "lower(sh.name) LIKE lower('%$searchQuery%')";
		else
			return "TRUE";
	}

	private static function getSortSql($params) {
		$orderBy = $params['OrderBy'];
		$isAscending = $params['IsAscending'];
		$isAscending = $isAscending ? json_decode($isAscending) : true;
		$isAscending = $isAscending ? "ASC" : "DESC"; 
		if($orderBy === "Name")
			$orderBy = "sh.name";
		else if ($orderBy === "Price")
			$orderBy = "sh.price";

		return "ORDER BY $orderBy $isAscending";
	}

	private static function getPaginationSql($params) {
		$page = $params['Page'];
		$limit = $params['Limit'];
		$offset = (intval($page) - 1) * intval($limit);
		$offset = "OFFSET $offset";
		$limit = "LIMIT $limit";
		
		return 
"$limit
$offset";
	}

	public static function mapShoesResponse($shoes) {
		$res = array();

		foreach ($shoes as $shoe) {
			$shoe = (object)$shoe;
			
			$resItem = ['id' => $shoe->id,
				'name' => $shoe->name,
				'image' => $shoe->image,
				'price' => intval($shoe->price),
				'brand' => ['id' => $shoe->brandid, 'name' => $shoe->brandname],
				'season' => ['id' => $shoe->seasonid, 'name' => $shoe->seasonname],
				'destination' => ['id' => $shoe->destinationid, 'name' => $shoe->destinationname],
				'ruSizes' => json_decode($shoe->ru_sizes),
			];

			array_push($res, $resItem);
		}

		return $res;
	}
}

?>