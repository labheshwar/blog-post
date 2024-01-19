<?php
session_start();

require_once 'utilities/user.php';
require_once 'utilities/blogposts.php';

$login_fail_message = '';
$title = '';
$text = ''; 

if (!is_user_loggedin()) {
    header("Location: index.php");
    exit(); // Always exit after sending a header redirect
}

if(isset($_POST["blog-title"]) && isset($_POST["blog-text"])) {
    $title = $_POST["blog-title"];
    $text = $_POST["blog-text"];
	$public = isset($_POST['publish']) ? 'true' : 'false';

    $success = add_post($title, $text, $public);

    $_SESSION["flash_message"] = $success ? "Post added" : "Post not added";
    
    if ($success) {
        header("Location: home.php");
        exit(); // Always exit after sending a header redirect
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="style.css">
    <title>Add Blogs</title>
</head>
<body>
    <?php include "header.php";?>
    
    <?php if(isset($_SESSION["flash_message"])):?>
        <div class="error-message"><?=$_SESSION["flash_message"];?></div>
        <?php unset($_SESSION["flash_message"]);
    endif;
    ?>

    <div style="text-align: center">
        <h1>Create a Blog</h1>
        
        <?php if($login_fail_message):?>
        <div class="error-message"><?=$login_fail_message;?></div>
        <?php endif;?>

        <form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
            <div>
                <label>Blog Title
                    <input type="text" name="blog-title" value="<?=$title?>" required />
                </label>
            </div>
            <div>
                <label>Text
                    <textarea name="blog-text" required><?=$text?></textarea>
                </label>
            </div>
            <div>
                <input type="submit" value="Publish" name="publish" />
                <input type="submit" value="Save" name="save-button" />
            </div>
        </form>
    </div>
</body>
</html>
