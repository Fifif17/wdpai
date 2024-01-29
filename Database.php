<?php

require_once "config.php";

class Database {
    public function connect(){
        try{
            $conn = new PDO(
                "pgsql:host=" . CONFIG::$dbHost . ";port=" . CONFIG::$dbPort . ";dbname=" . CONFIG::$dbName,
                CONFIG::$dbUser,
                CONFIG::$dbPass
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch(PDOException $e){
            die("Connection failed:".$e->getMessage());
        }
    }
}