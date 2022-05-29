<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once __DIR__ . "/../service/userService.php";

$app->get('/users', function (Request $request, Response $response) {
	$token = $request->getHeader('Authorization')[0];
	var_dump($token);
	$token = userService::readToken($token);
	var_dump($token);
	$sql = "SELECT login, name, fname, phone FROM users WHERE login = '$token'";
	//var_dump($sql);
	$conn = DB::connect();
	$query = pg_query($conn, $sql);
	if(!$query)
		throw new Exception('Неправильный токен');

	$user = pg_fetch_assoc($query);

	$response->getBody()->write(json_encode($user));
	return $response
		->withHeader('Content-Type', 'application/json');
});

$app->post('/users/register', function (Request $request, Response $response) {
	$body = json_decode($request->getBody());

	$login = $body->login;
	$password = $body->password;
	$name = $body->name;
	$fname = $body->fname;
	$phone = $body->phone;

	$user = ['login' => $login,
		'password' => hash('SHA512', $password),
		'name' => $name,
		'fname' => $fname,
		'phone' => $phone
	];

	$conn = DB::connect();
	$res = pg_insert($conn,'public.users', $user);
	$res = boolval($res);

	$response->getBody()->write(json_encode($res));
	return $response
		->withHeader('Content-Type', 'application/json');
});

$app->post('/users/login', function (Request $request, Response $response) {
	$body = json_decode($request->getBody());

	$login = $body->login;
	$password = $body->password;

	$conn = DB::connect();
	$user = pg_select($conn,'public.users', ['login' => $login]);
	if(!$user)
		throw new Exception('Пользователя с таким логином не существует');
	$user = $user[0];
	if(hash('SHA512', $password) != $user['password'])
		throw new Exception('Неправильный пароль');

	$token = userService::createToken($user['login']);
	
	$response->getBody()->write(json_encode($token));
	return $response
		->withHeader('Content-Type', 'application/json');
});

$app->put('/users', function (Request $request, Response $response, $args) {
	$token = $request->getHeader('Authorization')[0];
	$token = userService::readToken($token);
	$body = json_decode($request->getBody());

	$name = $body->name;
	$fname = $body->fname;
	$phone = $body->phone;

	$conn = DB::connect();

	$user = pg_select($conn,'public.users', ['login' => $token]);
	if(!$user)
		throw new Exception('Пользователя с таким логином не существует');
	
	$user = $user[0];
	$updatedUser= ['name' => $name, 'fname' => $fname, 'phone' => $phone];
	$res = pg_update($conn,'public.users',$updatedUser, ['id' => $user['id']]);
	$res = boolval($res);

	$response->getBody()->write(json_encode($res));
	return $response
		->withHeader('Content-Type', 'application/json');
});

$app->delete('/users', function (Request $request, Response $response, $args) {
	$conn = DB::connect();
	$token = $request->getHeader('Authorization')[0];
	$token = userService::readToken($token);
	$res = pg_delete($conn,'public.users', ['login' => $token], PGSQL_DML_STRING);
	//var_dump($res);
	$res = boolval($res);

	$response->getBody()->write(json_encode($res));
	return $response
		->withHeader('Content-Type', 'application/json');
});

?>