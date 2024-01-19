<?php
session_start();
require_once 'utilities/blogposts.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['_user']['user_id'];

    // Check if the user has already liked the post
    if (!has_user_liked_post($user_id, $post_id)) {
        // Add the user to the likes table
        add_user_to_like($user_id, $post_id);

        // Redirect back to the post page after liking
        header("Location: post.php?id=$post_id");
        exit();
    } else {
        // Redirect back to the post page if the user has already liked the post
        $_SESSION["flash_message"] = "You already liked this post.";
        header("Location: post.php?id=$post_id");
        exit();
    }
} else {
    // Redirect to home page if the request is invalid
    $_SESSION["flash_message"] = "Invalid request.";
    header("Location: home.php");
    exit();
}
?>
