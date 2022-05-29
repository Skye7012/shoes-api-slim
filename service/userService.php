<?php

require_once __DIR__ . "/..//config/db.php";

class userService {
	public static function getUserId($token) {
		$sql = "SELECT id FROM users WHERE login = '$token'";
		$conn = DB::connect();
		$query = pg_query($conn, $sql);
		if(!$query)
			throw new Exception('Неправильный токен');

		$user = pg_fetch_assoc($query);
		$userId = $user['id'];
		return $userId;
	}
}

?>