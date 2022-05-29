<?php

class DB {
	public static function connect() {
		$conn = pg_connect("host={host} port={port} dbname={dbname} user={user} password={password}") or die("Error connection");
		if (!$conn) {
			echo "Произошла ошибка.\n";
			exit;
		}
		return $conn;
	}
}

?>