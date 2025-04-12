<?php

require_once __DIR__ . '/../Services/AuthService.php';
require_once __DIR__ . '/../Repositories/UsersRepository.php';
require_once __DIR__ . '/../Services/UsersService.php';
require_once __DIR__ . '/../Models/User.php';

class UserController{

    public function login($data){
        if(!isset($data['email']) || !isset($data['password'])) {
            http_response_code(400);
            return json_encode(["status" => "error", "message" => "Email And Password Are Required"]);
        }
        $match = null;
        preg_match('/[a-z0-9_\-\+\.]+@[a-z0-9\-]+\.([a-z]{2,4})(?:\.[a-z]{2})?/i', $data['email'], $match);
        if(sizeof($match) < 1){
            http_response_code(400);
            header("Content-Type: application/json");
            return json_encode(["status" => "error", "message" => "email is not valid"]);
        }
        $service = new AuthService();
        return $service->login($data['email'],$data['password']);
    }

    public function changeSubUserPassword($data,$user){
        if(!isset($data['newPassword']) || !isset($data['id'])) {
            http_response_code(400);
            return json_encode(["status" => "error", "message" => "Email And Password Are Required"]);
        }

        $userSub = $user->getOwnedUsers();
        $validUser = false;
        foreach($userSub as $entry){
           if($entry['Id'] === $data['id']){
           $validUser = true;
           break;
           }
        }
        if(!$validUser)
        {
            http_response_code(403);
            return json_encode(["status" => "error", "message" => "This is not your user"]);
        }

        $serivce = new UsersService();
        return $serivce->changeSubUserPassword($data['id'],$data['newPassword']);
        
    }
    public function editSubUser($data,$user){
        if(!isset($data['id']) || !isset($data['newName'])) {
            http_response_code(400);
            return json_encode(["status" => "error", "message" => "Enter All Fields"]);
        }

        $service = new UsersService();

        return $service->editSubUser($data['id'],$data['newName'],$user->getId());
    }

    public function createSubUser($data,$user){
        if(!isset($data['email']) || !isset($data['password']) || !isset($data['name'])) {
            http_response_code(400);
            return json_encode(["status" => "error", "message" => "Enter All Fields"]);
        }

        $match = null;
        preg_match('/[a-z0-9_\-\+\.]+@[a-z0-9\-]+\.([a-z]{2,4})(?:\.[a-z]{2})?/i', $data['email'], $match);

        if(sizeof($match) < 1){
            http_response_code(400);
            header("Content-Type: application/json");
            return json_encode(["status" => "error", "message" => "email is not valid"]);
        }

        $service = new UsersService();
        return $service->createSubUser($data['email'],$data['password'],$data['name'],$user);
    }

    public function register($data){
        if(!isset($data['email']) || !isset($data['password']) || !isset($data['name'])) {
            http_response_code(400);
            return json_encode(["status" => "error", "message" => "Enter All Fields"]);
        }

        $match = null;
        preg_match('/[a-z0-9_\-\+\.]+@[a-z0-9\-]+\.([a-z]{2,4})(?:\.[a-z]{2})?/i', $data['email'], $match);

        if(sizeof($match) < 1){
            http_response_code(400);
            header("Content-Type: application/json");
            return json_encode(["status" => "error", "message" => "email is not valid"]);
        }
        $service = new AuthService();
        return $service->register($data['email'],$data['password'],$data['name']);
    }

    public function getUser($id){
        $user_data = UsersRepository::getUserDatabyId($id);
        if($user_data->num_rows >0){
            $res = $user_data->fetch_all()[0];
            $user = null;
            if($res[7] === 0){

                $user = new User($res[0],
                $res[1],
                $res[2],
                $res[3],
                $res[5],
                $res[6]);

                $UsersRes = UsersRepository::getSubUsers($user);
                if($UsersRes->num_rows>0){
                    $Users = [];
                    while($row = $UsersRes->fetch_assoc()){
                        $Users[] = $row;
                    }
                    $user->setOwnedUsers($Users);
                }
                $transactionResult = UsersRepository::getUserHistory($user);
                if ($transactionResult->num_rows > 0) {
                    $transactions = [];
                    while ($row = $transactionResult->fetch_assoc()) {
                        $transactions[] = $row; 
                    }
                    $user->setAccountHistory($transactions);
                }
            }
            else{
                $user = new SubUser($res[0],
                $res[1],
                $res[2],
                $res[3],
                $res[5],
                $res[6]);

                $Res = UsersRepository::getOwnerId($user);

                if ($Res->num_rows > 0) {  
                    $fetchResult = $Res->fetch_all(); 
                    if (!empty($fetchResult) && isset($fetchResult[0][0])) {
                        $user->setOwnerId($fetchResult[0][0]);
                    }
                }
            }

            return $user;
        }
    }
}

?>