<?php
require_once __DIR__ . '/../Controllers/UserController.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/SubUser.php';
header("Content-Type: application/json");
$data = json_decode(file_get_contents('php://input',),true);
if($data == null){
    http_response_code(405);
}
else{
    $controller = new UserController();
    $user = $controller->register($data);
}
?>