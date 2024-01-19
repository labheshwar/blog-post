<?php
session_start();
require_once 'utilities/blogposts.php';

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];

    // Retrieve the full blog post details from the database
    $blogpost = get_blogpost_by_id($post_id);

    if ($blogpost) {
        $author = $blogpost["user_full_name"];
        $post_title = $blogpost["post_title"];
        $post_body = $blogpost["post_body"];        
        $likes = $blogpost["likes"];
        $reads = $blogpost["_reads"];
        $post_date = $blogpost["post_date"];
        $post_date = date_create($post_date); // DateTime object
        $post_date = date_format($post_date,"jS, F, Y.");
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8" />
            <link rel="stylesheet" href="style.css">
            <title><?=$post_title?></title>
        </head>
        <body>
            <?php include "header.php";?>

            <div style="text-align: center">
                <h1><?=$post_title?></h1>
                <div class="blogauthor">By <?=$author?></div>
                <div><?=$post_body?></div>
                <div class="blogpostfooter">
                    <span class=""><small><i class="count blogdate"><?=$likes?></i> people like the blog.</small></span>
                    <span class=""><small><i class="count blogdate"><?=$reads?></i> people have read the blog.</small></span>
                    <div class=""><small>Posted on: <?=$post_date?></small></div>
                </div>
            </div>
        </body>
        </html>
        <?php
    } else {
        $_SESSION["flash_message"] = "Blog post not found.";
        header("Location: home.php");
        exit();
    }
} else {
    $_SESSION["flash_message"] = "Invalid request.";
    header("Location: home.php");
    exit();
}
?>
