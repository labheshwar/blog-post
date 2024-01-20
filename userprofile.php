<?php
session_start();
require_once 'utilities/user.php';
require_once 'utilities/blogposts.php';
require_once 'utilities/db.php';

if (!is_user_loggedin()) {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $user = get_user_by_id($user_id);

    if ($user) {
        $isCurrentUser = isset($_SESSION['_user']) && $_SESSION['_user']['user_id'] == $user['user_id'];
        $blogposts = get_all_posts_by_user_id($user_id);

        $isFollowing = false;
        if (!$isCurrentUser) {
            $currentUserId = $_SESSION['_user']['user_id'];
            $isFollowing = is_following($currentUserId, $user_id);
        }

        $totalFollowers = get_total_followers($user_id);

        $followButtonText = $isFollowing ? 'Unfollow' : 'Follow';

?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8" />
            <link rel="stylesheet" href="style.css">
            <title>User Profile</title>
        </head>

        <body>
            <?php include "header.php"; ?>

            <div style="text-align: center">
                <h1>User Profile</h1>
                <div class="user-details">
                    <p>User Name: <?= $user['user_full_name'] ?></p>
                    <?php if (!$isCurrentUser) : ?>
                        <p>Total Followers: <?= $totalFollowers ?></p>
                        <form method="POST" action="togglefollow.php">
                                <input type="hidden" name="user_id" value="<?= $user_id ?>">
                                <button type="submit"><?= $followButtonText ?></button>
                            </form>
                    <?php else : ?>
                        <button class="follow-button" disabled><?= $totalFollowers ?> people follows you</button>
                    <?php endif; ?>
                </div>
            </div>
            <div style="text-align: center">
                <h1>Latest blogs by <?= $user["user_name"] ?></h1>
                <div class="blog-wrapper">
                    <?php
                    if ($blogposts != null) :
                        foreach ($blogposts as $blogpost) :
                            $author = $blogpost["user_full_name"];
                            $post_id = $blogpost["post_id"];
                            $post_title = $blogpost["post_title"];
                            $post_body = $blogpost["post_body"];
                            $likes = $blogpost["likes"];
                            $reads = $blogpost["_reads"];
                            $post_date = $blogpost["post_date"]; // String object
                            $post_date = date_create($post_date); // DateTime object
                            $post_date = date_format($post_date, "jS, F, Y.");
                    ?>
                            <section class="blogpost">
                                <div style="color: #dff1f1;"><?= $post_body ?>. <a style="color: white; text-decoration: underline; font-size: 12px;" href="post.php?id=<?= $post_id ?>">read more...</a></div>
                                <div class="blogpostfooter">
                                    <!-- Note: Never expose database ids in urls -->
                                    <?php if ($likes > 0) : ?>
                                        <a href="postlikes.php?id=<?= $post_id ?>">
                                        <?php endif; ?>
                                        <span class="blogdate"><small><i class="count"><?= $likes ?></i> people like the blog.</small></span>
                                        <?php if ($likes > 0) : ?>
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($reads > 0) : ?>
                                        <a href="postreads.php?id=<?= $post_id ?>">
                                        <?php endif; ?>
                                        <span class="blogdate"><small><i class="count"><?= $reads ?></i> people have read the blog.</small></span>
                                        <?php if ($reads > 0) : ?>
                                        </a>
                                    <?php endif; ?>

                                    <div class="blogdate"><small>Posted on: <?= $post_date ?></small></div>
                                </div>
                            </section>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
        </body>

        </html>
<?php
    } else {
        $_SESSION["flash_message"] = "User not found.";
        header("Location: home.php");
        exit();
    }
} else {
    $_SESSION["flash_message"] = "Invalid request.";
    header("Location: home.php");
    exit();
}
?>