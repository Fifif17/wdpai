<?php

class User {
    private $email;
    private $login;
    private $password;
    private $profile_picture;
    private $firstname;
    private $lastname;
    private $phone;
    private $uid;


    public function __construct($email, $login, $password) {
        $this->email = $email;
        $this->login = $login;
        $this->password = $password;
        $this->profile_picture = '';
    }


    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }


    public function getLogin() {
        return $this->login;
    }

    public function setLogin($login) {
        $this->login = $login;
    }


    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password){
        $this->password = $password;
    }


    public function getProfilePic() {
        return $this->profile_picture;
    }

    public function setProfilePic($profile_pic) {
        $this->profile_picture = $profile_pic;
    }


    public function getFirstname() {
        return $this->firstname;
    }

    public function setFirstName($firstname){
        $this->firstname = $firstname;
    }


    public function getLastname() {
        return $this->lastname;
    }

    public function setLastname($lastname){
        $this->lastname = $lastname;
    }


    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone){
        $this->phone = $phone;
    }


    public function getId() {
        return $this->uid;
    }

    public function setId($uid) {
        $this->uid = $uid;
    }
}

?>