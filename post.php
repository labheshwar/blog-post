<?php
session_start();
require_once 'utilities/blogposts.php';

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];

    // Retrieve the full blog post details from the database
    $blogpost = get_blogpost_by_id($post_id);
    $user_id = isset($_SESSION["_user"]) ? $_SESSION["_user"]["user_id"] : null;

    if ($user_id) {
        $hasRead = has_user_read_post($user_id, $post_id);

        if (!$hasRead) {
            // Add the user to the BlogPostReads table
            add_user_to_read($user_id, $post_id);
        }
    }

    if ($blogpost) {
        $author = $blogpost["user_full_name"];
        $post_title = $blogpost["post_title"];
        $post_body = $blogpost["post_body"];
        $likes = $blogpost["likes"];
        $reads = !$hasRead ? $blogpost["_reads"] + 1 : $blogpost["_reads"];
        $post_date = $blogpost["post_date"];
        $post_date = date_create($post_date); // DateTime object
        $post_date = date_format($post_date, "jS, F, Y.");

        // Check if the user has already read the post

?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8" />
            <link rel="stylesheet" href="style.css">
            <title><?= $post_title ?></title>
        </head>

        <body>
            <?php include "header.php"; ?>

            <div style="text-align: center">
                <h1><?= $post_title ?></h1>
                <div class="">By <?= $author ?></div>
                <div><?= $post_body ?></div>
                <div class="blogpostfooter">
                    <div>
                        <?php if (!has_user_liked_post($_SESSION['_user']['user_id'], $post_id)) : ?>
                            <form method="POST" action="likepost.php">
                                <input type="hidden" name="post_id" value="<?= $post_id ?>">
                                <button type="submit">Like</button>
                            </form>
                        <?php else : ?>
                            <form method="POST" action="likepost.php">
                                <input type="hidden" name="post_id" value="<?= $post_id ?>">
                                <button type="submit" disabled>Liked</button>
                            </form>
                        <?php endif; ?>
                        <?php if ($likes > 0) : ?>
                            <a href="postlikes.php?id=<?= $post_id ?>">
                            <?php endif; ?>
                            <span class=""><small><i class="count blogdate"><?= $likes ?></i> people like the blog.</small></span>
                            <?php if ($likes > 0) : ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    <?php if ($reads > 0) : ?>
                        <a href="postreads.php?id=<?= $post_id ?>">
                        <?php endif; ?>
                        <span class=""><small><i class="count blogdate"><?= $reads ?></i> people have read the blog.</small></span>
                        <?php if ($reads > 0) : ?>
                        </a>
                    <?php endif; ?>
                    <div class=""><small>Posted on: <?= $post_date ?></small></div>
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