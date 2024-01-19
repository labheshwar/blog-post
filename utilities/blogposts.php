<?php
require_once 'db.php';

function get_all_posts() {
    try {
        $db_connection = db_connect();

        $select_statment = "
        SELECT DISTINCT b.post_id, b.user_id, post_title, SUBSTRING(post_body, 1, 150) as post_body, post_date, user_full_name, (SELECT COUNT(*) FROM BlogPostLikes WHERE post_id = b.post_id) as likes, (SELECT COUNT(*) FROM BlogPostReads WHERE post_id = b.post_id) as _reads FROM BlogPost b JOIN User u ON b.user_id = u.user_id LEFT JOIN BlogPostLikes bl on bl.post_id=b.post_id LEFT JOIN BlogPostReads br on br.post_id=b.post_id WHERE b.post_public = 1";

        $select_statment = $db_connection->prepare($select_statment);

        $select_statment->execute();
        $blogposts = $select_statment->fetchAll(PDO::FETCH_ASSOC);

        return !empty($blogposts) ? $blogposts : null;
    }
    catch (PDOException $e) {
        var_dump($e);
        return null;
    }
}

function get_users_who_like($post_id) {
    $select_statment = "
        SELECT u.user_full_name FROM BlogPostLikes b JOIN User u ON b.user_id = u.user_id WHERE post_id = :post_id;";

    return get_users($post_id, $select_statment);
}

function get_users_who_read($post_id) {
    $select_statment = "
        SELECT u.user_full_name FROM BlogPostReads b JOIN User u ON b.user_id = u.user_id WHERE post_id = :post_id;";

    return get_users($post_id, $select_statment);
}

function get_users($post_id, $select_statment) {
    try {
        $db_connection = db_connect();

        // $select_statment = "
        // SELECT u.user_full_name FROM :table b JOIN User u ON b.user_id = u.user_id WHERE post_id = :post_id;";

        $select_statment = $db_connection->prepare($select_statment);

        // $select_statment->bindParam(":table", $table);
        $select_statment->bindParam(":post_id", $post_id);

        var_dump($select_statment->execute());
        $users = $select_statment->fetchAll(PDO::FETCH_ASSOC);
        
        return !empty($users) ? $users : null;
    }
    catch (PDOException $e) {
        var_dump($e);
        return null;
    }
}

function add_post($title,$text, $public){
    try{
        $db_connection = db_connect();
        $user_id = $_SESSION['_user']['user_id'];
        $blog_post_title = $title;
        $blog_post_body = $text;
        $post_public = $public ? "1" : "0";
        $query = "INSERT INTO BlogPost (user_id, post_title, post_body, post_public) VALUES (:user_id, :post_title, :post_body, :post_public)";
        $statment = $db_connection->prepare($query);
    
        $statment->bindParam(":user_id", $user_id);
        $statment->bindParam(":post_title", $blog_post_title);
        $statment->bindParam(":post_body", $blog_post_body);
        $statment->bindParam(":post_public", $post_public);
    
        $statment->execute();
        return true;
    }
    catch (PDOException $e){
        return false;
    }
    
    
   
}

function get_blogpost_by_id($post_id) {
    try {
        $db_connection = db_connect();

        $select_statement = "
            SELECT
                b.post_id,
                b.user_id,
                post_title,
                post_body,
                post_date,
                user_full_name,
                (SELECT COUNT(*) FROM BlogPostLikes WHERE post_id = b.post_id) as likes,
                (SELECT COUNT(*) FROM BlogPostReads WHERE post_id = b.post_id) as _reads
            FROM
                BlogPost b
                JOIN User u ON b.user_id = u.user_id
            WHERE
                b.post_id = :post_id";

        $select_statement = $db_connection->prepare($select_statement);
        $select_statement->bindParam(":post_id", $post_id);
        
        if ($select_statement->execute()) {
            $blogpost = $select_statement->fetch(PDO::FETCH_ASSOC);
            return $blogpost ? $blogpost : null;
        } else {
            return null;
        }
    } catch (PDOException $e) {
        var_dump($e);
        return null;
    }
}

function has_user_read_post($user_id, $post_id) {
    try {
        $db_connection = db_connect();

        $select_statement = "
            SELECT COUNT(*) as count
            FROM BlogPostReads
            WHERE user_id = :user_id AND post_id = :post_id";

        $select_statement = $db_connection->prepare($select_statement);
        $select_statement->bindParam(":user_id", $user_id);
        $select_statement->bindParam(":post_id", $post_id);

        if ($select_statement->execute()) {
            $result = $select_statement->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        var_dump($e);
        return false;
    }
}

function add_user_to_read($user_id, $post_id) {
    try {
        $db_connection = db_connect();

        $insert_statement = "
            INSERT INTO BlogPostReads (user_id, post_id)
            VALUES (:user_id, :post_id)";

        $insert_statement = $db_connection->prepare($insert_statement);
        $insert_statement->bindParam(":user_id", $user_id);
        $insert_statement->bindParam(":post_id", $post_id);

        $insert_statement->execute();
    } catch (PDOException $e) {
        var_dump($e);
    }
}

function has_user_liked_post($user_id, $post_id) {
    try {
        $db_connection = db_connect();

        $select_statement = "
            SELECT COUNT(*) as count
            FROM BlogPostLikes
            WHERE user_id = :user_id
            AND post_id = :post_id";

        $select_statement = $db_connection->prepare($select_statement);
        $select_statement->bindParam(":user_id", $user_id);
        $select_statement->bindParam(":post_id", $post_id);

        $select_statement->execute();
        $result = $select_statement->fetch(PDO::FETCH_ASSOC);

        return $result['count'] > 0;
    } catch (PDOException $e) {
        var_dump($e);
        return false;
    }
}

function add_user_to_like($user_id, $post_id) {
    try {
        $db_connection = db_connect();

        $insert_statement = "
            INSERT INTO BlogPostLikes (user_id, post_id)
            VALUES (:user_id, :post_id)";

        $insert_statement = $db_connection->prepare($insert_statement);
        $insert_statement->bindParam(":user_id", $user_id);
        $insert_statement->bindParam(":post_id", $post_id);

        $insert_statement->execute();
        return true;
    } catch (PDOException $e) {
        var_dump($e);
        return false;
    }
}