<?php

require_once 'AppController.php';

class DefaultController extends AppController {

    public function login() {
        $this->render('login');
    }

    public function register() {
        $this->render('register');
    }

    public function logout() {
        $this->render('logout');
    }

    public function mainPage() {
        $this->render('mainPage');
    }

    public function myAccount() {
        $this->render('myAccount');
    }
    
}