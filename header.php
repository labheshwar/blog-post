<?php

$user = isset($_SESSION['_user']) ? $_SESSION['_user'] : null;
if(isset($user)) {
    $login_menu_item = "Hello " . $user["user_full_name"] . ' (<a href="logout.php">Logout</a>)';
}

?>
<header>
    <nav>
        <ul style="column-count: 4; list-style: none">
            <li><a href="home.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <?php if(isset($login_menu_item)):?>
                <li><a href="addblog.php">Add blog</a></li>
                <li><?=$login_menu_item?></li>
            <?php endif;?>
        </ul>
    </nav>
</header>
