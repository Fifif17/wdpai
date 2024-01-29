<?php

require_once 'Repository.php';
require_once __DIR__.'/../models/User.php';

class UserRepository extends Repository {

    public function getUser(string $email, string $password): ?User {

        $isLogin = false;
        
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM users WHERE email = :email
        ');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Password veryfication
        if (is_array($user)) {
            if (password_verify($password, $user['password'])) {
                $isLogin = true;
            }
        }

        if ($user == false or $isLogin == false) {
            return null;
        }

        return new User(
            $user['email'],
            $user['login'],
            $user['password']
        );
    }


    public function addUser(User $user) {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO user_details (firstname, lastname, phone)
            VALUES (?, ?, ?)
        ');

        $stmt->execute([
            $user->getFirstname(),
            $user->getLastname(),
            $user->getPhone()
        ]);


        $stmt = $this->database->connect()->prepare('
            INSERT INTO users (id_user_type, login, password, email, id_user_detail)
            VALUES (2, ?, ?, ?, ?);
        ');

        $stmt->execute([
            $user->getLogin(),
            $user->getPassword(),
            $user->getEmail(),
            $this->getUserDetailsId($user)
        ]);
    }


    public function getUserDetailsId(User $user): int {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM user_details WHERE firstname = ? AND lastname = ? AND phone = ?
        ');
        $stmt->execute([
            $user->getFirstname(),
            $user->getLastname(),
            $user->getPhone()
        ]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data['id_user_detail'];
    }


    public function getUid($email): int {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM users WHERE email = :email
        ');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user['id_user'];
    }
}

?>