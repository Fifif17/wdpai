<?php

require_once 'AppController.php';
require_once __DIR__ . './../models/User.php';

class SecurityController extends AppController {

    public function login() {
        $user = new User('test_user1@test.com', 'test_user1', 'test');
        
        // $this->isPost() {
        //     return $this->render('login');
        // }

        $email = $_POST['email'];
        $password = $_POST['password'];

        if ($user->getEmail() !== $email) {
            return $this->render('login', ['messages' => ['User with this email doesn\'t exist']]);
        }

        if ($user->getPassword() !== $password) {
            return $this->render('login', ['messages' => ['Wrong password']]);
        }

        return $this->render('mainPage');

        // $url = "http://$_SERVER[HTTP_POST]";
        // header("Location: {$url}/mainPage");
    }


}