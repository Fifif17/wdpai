<?php

require_once "config.php";

class Database {
    public function connect()
    {
        try {
            $pdo = new PDO(
                "pgsql:host=" . Config::$dbHost . ";port=" . Config::$dbPort . ";dbname=" . Config::$dbName, Config::$dbUser, Config::$dbPass
            );
            // $pdo = new PDO('pgsql:host=localhost;dbname=fiszlet', 'postgres', 'kwakwa5!');

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        }
        catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}