<?php
require_once 'utilities/db.php';
require_once 'utilities/user.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();

    if (is_user_loggedin()) {
        $currentUserId = $_SESSION['_user']['user_id'];
        $targetUserId = $_POST['user_id'];

        $result = toggle_follow($currentUserId, $targetUserId);

        if ($result === "followed") {
            $followButtonText = "Unfollow";
            $totalFollowers++;
        } elseif ($result === "unfollowed") {
            $followButtonText = "Follow";
            $totalFollowers--;
        }
        header("Location: userprofile.php?id=" . $targetUserId);
        exit();
    }
}

header("Location: index.php");
exit();
