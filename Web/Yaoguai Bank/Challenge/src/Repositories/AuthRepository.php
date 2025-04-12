<?php

require_once __DIR__ . '/../Core/db.php';

class AuthRepository extends User{

    public static function Authenticate($email,$password){
        $query = "SELECT * FROM bankaccounts WHERE email = ? and Password = ?";
        $param = [$email,md5($password)];
        $connection = DB::getInstance();
        $statment = $connection->getConnection()->prepare($query);
        $statment->bind_param("ss",...$param);
        $statment->execute();
        return $statment->get_result();
    }

    public static function Register($email, $password, $name) {
        $connection = DB::getInstance()->getConnection();
    
        if (self::emailExists($connection, $email)) {
            return ["status" => "error", "message" => "Email is already registered"];
        }
    
        // unique account number
        $accountNumber = self::generateAccNumber($connection);
    
        $hashedPassword = md5($password);
    
        $query = "INSERT INTO bankaccounts (AccountNumber, Name, email, Password) VALUES (?, ?, ?, ?)";
        $statement = $connection->prepare($query);
        $statement->bind_param("dsss", $accountNumber, $name, $email, $hashedPassword);
        
        if ($statement->execute()) {
            return ["status" => "success", "message" => "User registered successfully", "accountNumber" => $accountNumber];
        } else {
            return ["status" => "error", "message" => "Registration failed: " . $statement->error];
        }
    }
    
    private static function emailExists($connection, $email) {
        $query = "SELECT 1 FROM bankaccounts WHERE email = ?";
        $statement = $connection->prepare($query);
        $statement->bind_param("s", $email);
        $statement->execute();
        $result = $statement->get_result();
        
        return $result->num_rows > 0;
    }
    
    private static function generateAccNumber($connection) {
        do {
            $accountNumber = rand(10000000, 99999999);
            
            $query = "SELECT 1 FROM bankaccounts WHERE AccountNumber = ?";
            $statement = $connection->prepare($query);
            $statement->bind_param("d", $accountNumber);
            $statement->execute();
            $result = $statement->get_result();
        } while ($result->num_rows > 0); 
    
        return $accountNumber;
    }
    
    

}

?>