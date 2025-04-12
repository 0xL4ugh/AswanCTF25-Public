<?php

require_once __DIR__ . '/../Core/db.php';

class TransactionsRepository{

    

    public static function addBalance($accountnum,$newBalance){
        $connection = DB::getInstance()->getConnection();
        $query = "UPDATE bankaccounts SET balance = ? + balance WHERE AccountNumber = ?";
        $statement = $connection->prepare($query);
        $param = [$newBalance,$accountnum];
        $statement->bind_param("dd",...$param);
        $statement->execute();
        return $statement->affected_rows;
    }
    public static function subtractBalance($accountnum,$newBalance){
        $connection = DB::getInstance()->getConnection();
        $query = "UPDATE bankaccounts SET balance = balance - ? WHERE AccountNumber = ?";
        $statement = $connection->prepare($query);
        $param = [$newBalance,$accountnum];
        $statement->bind_param("dd",...$param);
        $statement->execute();
        return $statement->affected_rows;
    }
    public static function addTransactionHistory($accountnum,$amount){
        $connection = DB::getInstance()->getConnection();
        $query = "INSERT INTO transactions (FromAcc,ToAcc,Amount,Date) VALUES (?,?,?,CURRENT_TIMESTAMP)";
        $statment = $connection->prepare($query);
        $param = [$accountnum,$accountnum,$amount];
        $statment->bind_param("ddd",...$param);
        $statment->execute();
        return $statment->affected_rows;
    }

    public static function getAccountsNumber(){
        $query = "SELECT AccountNumber FROM bankaccounts";
        $connection = DB::getInstance();
        $statment = $connection->getConnection()->prepare($query);
        $statment->execute();
        return $statment->get_result();
    }
}


?>