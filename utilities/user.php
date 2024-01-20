<?php
require_once 'db.php';

function do_login($user_name, $user_pass) {
    try {
        $db_connection = db_connect();

        $select_statment = "
        SELECT * FROM User
        WHERE user_name = :user_name";

        $select_statment = $db_connection->prepare($select_statment);
        $select_statment->bindParam(":user_name", $user_name);

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

function get_user_by_id($user_id) {
    try {
        $db_connection = db_connect();

        $select_statement = "
        SELECT * FROM User
        WHERE user_id = :user_id";

        $select_statement = $db_connection->prepare($select_statement);
        $select_statement->bindParam(":user_id", $user_id);

        $select_statement->execute();
        $user = $select_statement->fetch(PDO::FETCH_ASSOC);

        return !empty($user) ? $user : null;
    } catch (PDOException $e) {
        var_dump($e);
        echo "DB connection failure";
        exit();
    }
}

function is_following($currentUserId, $user_id) {
    try {
        $db_connection = db_connect();

        $select_statement = "
        SELECT * FROM UserFollower
        WHERE follower_id = :currentUserId AND user_id = :user_id";

        $select_statement = $db_connection->prepare($select_statement);
        $select_statement->bindParam(":currentUserId", $currentUserId);
        $select_statement->bindParam(":user_id", $user_id);

        $select_statement->execute();
        $follow = $select_statement->fetch(PDO::FETCH_ASSOC);

        return !empty($follow);
    } catch (PDOException $e) {
        var_dump($e);
        echo "DB connection failure";
        exit();
    }
}

function get_total_followers($user_id) {
    try {
        $db_connection = db_connect();

        $select_statement = "
        SELECT COUNT(*) as total_followers FROM UserFollower
        WHERE user_id = :user_id";

        $select_statement = $db_connection->prepare($select_statement);
        $select_statement->bindParam(":user_id", $user_id);

        $select_statement->execute();
        $result = $select_statement->fetch(PDO::FETCH_ASSOC);

        return !empty($result) ? $result['total_followers'] : 0;
    } catch (PDOException $e) {
        var_dump($e);
        echo "DB connection failure";
        exit();
    }
}

function toggle_follow($currentUserId, $user_id) {
    try {
        $db_connection = db_connect();

        if (is_following($currentUserId, $user_id)) {
            $delete_statement = "
                DELETE FROM UserFollower
                WHERE follower_id = :currentUserId AND user_id = :user_id";
                
            $delete_statement = $db_connection->prepare($delete_statement);
            $delete_statement->bindParam(":currentUserId", $currentUserId);
            $delete_statement->bindParam(":user_id", $user_id);

            $delete_statement->execute();
            
            return "unfollowed";
        } else {
            $insert_statement = "
                INSERT INTO UserFollower (follower_id, user_id)
                VALUES (:currentUserId, :user_id)";
                
            $insert_statement = $db_connection->prepare($insert_statement);
            $insert_statement->bindParam(":currentUserId", $currentUserId);
            $insert_statement->bindParam(":user_id", $user_id);

            $insert_statement->execute();
            
            return "followed";
        }
    } catch (PDOException $e) {
        var_dump($e);
        echo "DB connection failure";
        exit();
    }
}
?>
