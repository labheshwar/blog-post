<?php
require_once 'config.php';

function db_connect() {
    try {
        $db_connection = new PDO("mysql:host=localhost;dbname=" . DB_NAME, DB_USER, DB_PASS);
        return $db_connection;
    }
    catch (PDOException $e) {
        var_dump($e);
        echo "DB connection failure";
        exit();
    }
}