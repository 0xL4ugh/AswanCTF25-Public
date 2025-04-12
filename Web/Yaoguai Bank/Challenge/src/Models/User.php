<?php

require_once 'Person.php';

class User extends Person {
    private $AccountNumber;
    private $balance;
    private $isPremium;
    private $Accounthistory;
    private $Users;

    public function __construct($id, $AccountNumber, $name, $email, $balance, $isPremium, $Accounthistory = null,$Users = null) {
        $this->id = $id;
        $this->AccountNumber = $AccountNumber;
        $this->name = $name;
        $this->email = $email;
        $this->balance = $balance;
        $this->isPremium = $isPremium;
        $this->Accounthistory = $Accounthistory;
        $this->Users = $Users;
    }

    public function toJson(){
        header("Content-Type: application/json");
        return json_encode(array(
            "AccountNumber" => $this->getAccountNumber(),
            "Name" => $this->getName(),
            "email" => $this->getEmail(),
            "isPermium" => $this->getIsPremium(),
            "Balance" => $this->getBalance(),
            "Users" => $this->getOwnedUsers(),
            "Histoy" => $this->getAccountHistory(),
        ));
    }


    public function getId() {
        return $this->id;
    }

    public function getAccountNumber() {
        return $this->AccountNumber;
    }


    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getBalance() {
        return $this->balance;
    }

    public function getIsPremium() {
        return $this->isPremium;
    }

    public function getAccountHistory() {
        return $this->Accounthistory;
    }

    public function getOwnedUsers() {
        return $this->Users;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setAccountNumber($AccountNumber): void {
        $this->AccountNumber = $AccountNumber;
    }


    public function setName($name): void {
        $this->name = $name;
    }

    public function setEmail($email): void {
        $this->email = $email;
    }

    public function setBalance($balance): void {
        $this->balance = $balance;
    }

    public function setIsPremium($isPremium): void {
        $this->isPremium = $isPremium;
    }

    public function setAccountHistory($Accounthistory): void {
        $this->Accounthistory = $Accounthistory;
    }

    public function setOwnedUsers($Users): void{
        $this->Users = $Users;
    }

    public function printData(): void {
        echo "User Info:\n";
        echo "ID: {$this->id}\n";
        echo "Account Number: {$this->AccountNumber}\n";
        echo "Name: {$this->name}\n";
        echo "Email: {$this->email}\n";
        echo "Balance: {$this->balance}\n";
        echo "Premium User: " . ($this->isPremium ? 'Yes' : 'No') . "\n";

        echo "Account History:\n";
        if (is_array($this->Accounthistory)) {
            foreach ($this->Accounthistory as $entry) {
                echo "- $entry\n";
            }
        } else {
            echo $this->Accounthistory . "\n";
        }

        echo "Users:\n";
        if (is_array($this->Users)) {
            foreach ($this->Users as $entry) {
                echo "- $entry\n";
            }
        } else {
            echo $this->Users . "\n";
        }
    }
}

?>
