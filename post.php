<?php
session_start();
require_once 'utilities/blogposts.php';

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];

    $blogpost = get_blogpost_by_id($post_id);
    $user_id = isset($_SESSION["_user"]) ? $_SESSION["_user"]["user_id"] : null;

    if ($user_id) {
        $alreadyRead = already_read($user_id, $post_id);

        if (!$alreadyRead) {
            mark_as_read($user_id, $post_id);
        }
    }

    if ($blogpost) {
        $author = $blogpost["user_full_name"];
        $post_title = $blogpost["post_title"];
        $post_body = $blogpost["post_body"];
        $likes = $blogpost["likes"];
        $reads = !$alreadyRead ? $blogpost["_reads"] + 1 : $blogpost["_reads"];
        $post_date = $blogpost["post_date"];
        $post_date = date_create($post_date);
        $post_date = date_format($post_date, "jS, F, Y.");
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

            <div class="post-section">
                <div class="title-wrapper">
                    <h1><?= $post_title ?></h1>
                    <p class="">By <?= $author ?></p>
                </div>
                <div><?= $post_body ?></div>
                <div class="blogpostfooter">
                    <div class="post-like">
                        <?php if (!already_liked($_SESSION['_user']['user_id'], $post_id)) : ?>
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
        header("Location: home.php");
        exit();
    }
} else {
    header("Location: home.php");
    exit();
}
?>