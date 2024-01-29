<?php

require_once 'AppController.php';
require_once __DIR__ . './../models/User.php';
require_once __DIR__ . './../repository/UserRepository.php';

class SecurityController extends AppController {

    private $userRepository;

    public function __construct() {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    public function login() {     
        // Also starts session
        if ($this->isLoggedIn()) {
            return $this->redirectTo('mainPage');
        }

        if(!$this->isPost()){
            return $this->render('login');
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $this->userRepository->getUser($email, $password);

        if (!$user) {
            return $this->render('login', ['messages' => ['User doesn\'t exist']]);
        }

        if ($user->getEmail() !== $email) {
            return $this->render('login', ['messages' => ['User with this email doesn\'t exist']]);
        }

        $_SESSION['uid'] = $this->userRepository->getUid($email);

        return $this->redirectTo('mainPage');
    }


    public function register() {
        if ($this->isLoggedIn()) {
            return $this->redirectTo('mainPage');
        }

        if(!$this->isPost()){
            return $this->render('register');
        }

        $email = $_POST['email'];
        $login = $_POST['login'];
        $password = $_POST['password'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $phone = $_POST['phone'];

        $user = new User($email, $login , password_hash($password, PASSWORD_DEFAULT));
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setPhone($phone);

        $this->userRepository->addUser($user);
        return $this->redirectTo('login');
    }


    public function logout() {
        if ($this->isLoggedIn()) {
            unset($_SESSION['uid']);
        }

        session_unset();
        session_destroy();

        return $this->redirectTo('mainPage');
    }
}
