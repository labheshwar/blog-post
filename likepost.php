<?php
session_start();
require_once 'utilities/blogposts.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['_user']['user_id'];

    if (!already_liked($user_id, $post_id)) {
        like_post($user_id, $post_id);

        header("Location: post.php?id=$post_id");
        exit();
    } else {
        $_SESSION["flash_message"] = "You already liked this post.";
        header("Location: post.php?id=$post_id");
        exit();
    }
} else {
    $_SESSION["flash_message"] = "Invalid request.";
    header("Location: home.php");
    exit();
}
?>
