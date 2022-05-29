<?php

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/appSettings.php";

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

	public static function createToken($token) {
		return self::encrypt($token);
	}

	public static function readToken($token) {
		return self::decrypt($token);
	}

	private static function getEncryptKey() {
		return appSettings::getEncryptKey();
	}
	private static function getCiphering() {
		return "AES-192-CBC";
	}
	private static function encrypt($text) {
		return openssl_encrypt($text, self::getCiphering(), self::getEncryptKey());
	}
	private static function decrypt($text) {
		return openssl_decrypt($text, self::getCiphering(), self::getEncryptKey());
	}
}

?>