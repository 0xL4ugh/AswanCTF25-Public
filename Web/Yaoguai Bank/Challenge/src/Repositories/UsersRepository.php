<?php
require_once __DIR__ . '/../Core/db.php';
require_once __DIR__ . '/../Models/User.php';

class UsersRepository{
    public static function getUserHistory($user){
        $query = "SELECT * FROM transactions WHERE FromAcc = ?";
        $connection = DB::getInstance();
        $statment = $connection->getConnection()->prepare($query);
        $AccountNumber = $user->getAccountNumber();
        $statment->bind_param("d", $AccountNumber);
        $statment->execute();
        return $statment->get_result();
    }

    public static function getSubUsers($user){
        $query = "SELECT BA.Id,Name,Email FROM bankaccounts BA JOIN users US ON BA.Id = US.UserId WHERE OwnerId = ?";
        $connection = DB::getInstance();
        $statement = $connection->getConnection()->prepare($query);
        $OwnerId = $user->getId();
        $statement->bind_param("d",$OwnerId);
        $statement->execute();
        return $statement->get_result();
    }

    public static function getOwnerId($user){
        $query = "SELECT OwnerId from users WHERE UserId = ?";
        $connection = DB::getInstance();
        $statement = $connection->getConnection()->prepare($query);
        $OwnerId = $user->getId();
        $statement->bind_param("d",$OwnerId);
        $statement->execute();
        return $statement->get_result();
    }

    public static function getUserDatabyId($id){
        $query = "SELECT * from bankaccounts WHERE Id = ?";
        $connection = DB::getInstance();
        $statement = $connection->getConnection()->prepare($query);
        $statement->bind_param("d",$id);
        $statement->execute();
        return $statement->get_result();
    }

    public static function getUserDatabyEmail($email){
        $query = "SELECT * from bankaccounts WHERE email = ?";
        $connection = DB::getInstance();
        $statement = $connection->getConnection()->prepare($query);
        $statement->bind_param("s",$email);
        $statement->execute();
        return $statement->get_result();
    }

    public static function getUserDataByAccountnum($accountnum){
        $query = "SELECT * from bankaccounts WHERE AccountNumber = ?";
        $connection = DB::getInstance();
        $statement = $connection->getConnection()->prepare($query);
        $statement->bind_param("d",$accountnum);
        $statement->execute();
        return $statement->get_result();
    }

    public static function upgradeToPremium($accountnum,$premium=0){
        $query = "UPDATE bankaccounts SET Premium=? WHERE AccountNumber= ?";
        $connection = DB::getInstance();
        $statement = $connection->getConnection()->prepare($query);
        $statement->bind_param("dd",$premium,$accountnum);
        $statement->execute();
        return $statement->get_result();
    }
    private static function emailExists($connection, $email) {
        $query = "SELECT 1 FROM bankaccounts WHERE email = ?";
        $statement = $connection->prepare($query);
        $statement->bind_param("s", $email);
        $statement->execute();
        $result = $statement->get_result();
        
        return $result->num_rows > 0;
    }

    private static function getUserId($connection,$email){
        $query = "SELECT Id from bankaccounts where email = ?";
        $statement = $connection->prepare($query);
        $statement->bind_param("s",$email);
        $statement->execute();
        $res =$statement->get_result()->fetch_all()[0][0];
        return $res;
    }
    public static function createSubUser($email,$password,$name,$Owner){
        $connection = DB::getInstance()->getConnection();
    
        if (self::emailExists($connection, $email)) {
            return json_encode(["status" => "error", "message" => "Email is already registered"]);
        }

        $accountNumber = $Owner->getAccountNumber();
        $premium = $Owner->getIsPremium();
        $balance = $Owner->getBalance();
    
        $hashedPassword = md5($password);
        $query = "INSERT INTO bankaccounts (AccountNumber, Name, email, Password,balance,Premium,SubUser) VALUES (?, ?, ?, ?,?,?,1)";
        $statement = $connection->prepare($query);
        $statement->bind_param("dsssdd", $accountNumber, $name, $email, $hashedPassword,$balance,$premium);
        $statement->execute();
        $query = "INSERT INTO users (UserId,OwnerId) VALUES (?,?)";
        $statement2 = $connection->prepare($query);
        $userid= self::getUserId($connection,$email);
        $OwnerId = $Owner->getId();
        $statement2->bind_param("dd",$userid,$OwnerId);
        $statement2->execute();
        return $statement->get_result();
    }

    public static function editSubUser($id,$name) {
        $query = "UPDATE bankaccounts SET Name=? WHERE Id= ?";
        $connection = DB::getInstance();
        $statement = $connection->getConnection()->prepare($query);
        $statement->bind_param("sd",$name,$id);
        $statement->execute();
        return $statement->get_result();
    }

    public static function EnsureEditUserAuthority($id,$OwnerId){
        $query = "UPDATE users SET OwnerId = ? WHERE UserId = ?";
        $connection = DB::getInstance();
        $statement = $connection->getConnection()->prepare($query);
        $statement->bind_param("dd",$OwnerId,$id);
        $statement->execute();
        return $statement->get_result();
    }

    public static function changeSubUserpassword($id,$newPass){
        $query = "UPDATE bankaccounts SET Password=? WHERE id= ?";
        $connection = DB::getInstance();
        $statement = $connection->getConnection()->prepare($query);
        $statement->bind_param("sd",$newPass,$id);
        $statement->execute();
        return $statement->get_result();
    }
}


?>