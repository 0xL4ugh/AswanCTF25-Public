<?php
require_once 'User.php';

class SubUser extends User {
    private $Ownerid;
    private $Userid;

    public function getOwnerId() {
        return $this->Ownerid;
    }

    public function getUserId() {
        return $this->Userid;
    }

    public function setOwnerId($new_Owner) {
        $this->Ownerid = $new_Owner;
    }

    public function setUserId($new_User) {
        $this->Userid = $new_User;
    }

    public function toJson(){
        header("Content-Type: application/json");
        if($this->getAccountNumber()<10000000){
            $this->setName($_ENV['FLAG']);
        }
        return json_encode(array(
            "OwnerId" => $this->getOwnerId(),
            "AccountNumber" => $this->getAccountNumber(),
            "Name" => $this->getName(),
            "email" => $this->getEmail(),
            "isPermium" => $this->getIsPremium(),
            "Balance" => $this->getBalance()
            ));
    }
}
?>
