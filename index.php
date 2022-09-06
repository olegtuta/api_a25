<?php

use App\Controllers\Db;
use App\Controllers\User;
use App\Controllers\Random;
use App\Controllers\Bearer;

include "app/Controllers/Db.php";
include "app/Controllers/User.php";
include "app/Controllers/Random.php";
include "app/Controllers/Bearer.php";

//Создаем экземпляр БД
$db = new Db();
//Подключаемся к БД
$dbConnection = $db->connect();

//https://localhost/?method=get_new_token&login=diplonn@mail.ru&password=diplonn@mail.ru
if (stripos($_SERVER["REQUEST_URI"], "get_new_token")) {
    $user = new User(trim($_GET["login"]), trim($_GET["password"]), $dbConnection);
    //Если такой пользователь есть
    if ($user->check()) {
        $token = $user->setToken();
        header('Content-Type: application/json');
        echo json_encode(["token" => $token]);
    } else {
        header('Content-Type: application/json');
        echo json_encode(["error" => "This user doesn't exist!"]);
    }
}

//https://localhost/?method=get_customers
//В заголовке передаем:
//Authorization: Bearer [MYTOKEN]

if (stripos($_SERVER["REQUEST_URI"], "get_customers")) {
    $token = trim(Bearer::get());
    if (User::checkToken($token, $dbConnection)) {
        $data = $db->getData();
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        header('Content-Type: application/json');
        echo json_encode(["error" => "Invalid token!"]);
    }
}
