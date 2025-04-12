<?php

require_once __DIR__. '/../Models/User.php';
require_once __DIR__. '/../Models/SubUser.php';
require_once __DIR__ . '/../Repositories/AuthRepository.php';
require_once __DIR__ . '/../Repositories/UsersRepository.php';

class AuthService{

    public function login($email,$password){
        
        $res = AuthRepository::Authenticate($email,$password);
        if($res->num_rows == 0){
            http_response_code(401);
            echo json_encode(array("Status" => "failed", "Msg" => 'Wrong Username or Password'));
            return;
        }
        else{
            $res = $res->fetch_all()[0];
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

    public function register($email,$password,$name){
        $res = AuthRepository::Register($email,$password,$name);
        if($res['status'] !== "success"){
            http_response_code(400);
        }

        return $res;
        
    }
}

?>