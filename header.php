<?php
$user = isset($_SESSION['_user']) ? $_SESSION['_user'] : null;
if (isset($user)) {
    $login_menu_item = "Hello " . $user["user_full_name"] . ' (<a href="logout.php">Logout</a>)';
}
?>

<header>
    <ul class="header-menu">
        <li><a href="home.php" class="menu-item">Home</a></li>
        <li><a href="about.php" class="menu-item">About</a></li>
        <?php if (isset($login_menu_item)) : ?>
            <li><a href="addblog.php" class="menu-item">Add Blog</a></li>
            <li class="login-menu-item"><?= $login_menu_item ?></li>
        <?php endif; ?>
    </ul>
</header>