<?php

class Connection{

    public function getConnection(){
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=facebook', 'root', 'binarystar');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
}