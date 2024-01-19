<?php
require_once 'db.php';

function do_login($user_name, $user_pass) {
    try {
        $db_connection = db_connect();

        // $select_statment = "
        // SELECT * FROM User
        // WHERE user_name = :user_name AND user_pass = :user_pass";

        $select_statment = "
        SELECT * FROM User
        WHERE user_name = :user_name";

        $select_statment = $db_connection->prepare($select_statment);
        $select_statment->bindParam(":user_name", $user_name);
        // $select_statment->bindParam(":user_pass", $user_pass);

        $select_statment->execute();
        $user = $select_statment->fetch(PDO::FETCH_ASSOC);

        if(!empty($user)) {
            $user_pass_db = $user["user_pass"];
            return password_verify($user_pass, $user_pass_db)?
                    $user : null;
        }

        return null;
    }
    catch (PDOException $e) {
        var_dump($e);
        echo "DB connection failure";
        exit();
    }
}

function is_user_loggedin() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $user = isset($_SESSION["_user"]) ? $_SESSION["_user"] : null;
    return !empty($user);
}
