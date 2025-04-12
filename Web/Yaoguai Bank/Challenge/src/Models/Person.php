<?php

class Person {
    protected $id;
    protected $name;
    protected $email;


    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setId($id): void {
        $this->id = $id;
    }


    public function setName($name): void {
        $this->name = $name;
    }

    public function setEmail($email): void {
        $this->email = $email;
    }

}

?>
