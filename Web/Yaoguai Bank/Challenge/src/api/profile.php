<?php

require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Controllers/TransactionController.php';
require_once __DIR__ . '/../Controllers/UserController.php';
header("Content-Type: application/json");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    return 0;
}

if(isset($_SESSION['userid'])){
    
    $user_id = intval($_SESSION['userid']);
    $controller = new UserController();
    $user = $controller->getUser($user_id);
   
    echo $user->toJson();
}

else{
    http_response_code(403);
    echo "Forbidden";
    session_unset();
    session_destroy();
}

?>