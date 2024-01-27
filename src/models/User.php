<?php


class User {
    private string $email;
    private string $username;
    private string $password;


    public function __construct($email, $username, $password) {
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
    }


    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }


    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }


    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password){
        $this->password = $password;
    }



}

?>