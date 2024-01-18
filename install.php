<?php

require_once 'config.php';
$first_installation = true;

try {
    $db_connection = new PDO("mysql:host=localhost;dbname=" . DB_NAME, DB_USER, DB_PASS);
}
catch (PDOException $e) {
    $first_installation = false;
}

if ($first_installation) {
    echo "<h1>Already installed.</h1>";
    exit();
}

$db_blog_host= "CREATE DATABASE IF NOT EXISTS " . DB_NAME;

$table_user = "
CREATE TABLE IF NOT EXISTS User (
    user_id int AUTO_INCREMENT,
    user_name varchar(50) NOT NULL,
    user_pass varchar(100) NOT NULL,
    user_full_name varchar(100) NOT NULL,
    CONSTRAINT PK_user_id_User PRIMARY KEY (user_id)
);";

$table_user_follower = "
CREATE TABLE IF NOT EXISTS UserFollower (
    user_id int,
    follower_id int,
    CONSTRAINT PK_user_id_follower_id_UserFollower PRIMARY KEY (user_id, follower_id),
    CONSTRAINT FK_user_id_User_UserFollower FOREIGN KEY (user_id) REFERENCES User (user_id) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT FK_follower_id_User_UserFollower FOREIGN KEY (follower_id) REFERENCES User (user_id) ON DELETE RESTRICT ON UPDATE RESTRICT
);";

$table_blogpost = "
CREATE TABLE IF NOT EXISTS BlogPost (
    post_id int AUTO_INCREMENT,
    user_id int,
    post_title varchar(200) NOT NULL,
    post_body text NOT NULL,
    post_public boolean NOT NULL,
    post_date datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT PK_blogpost_post_id PRIMARY KEY (post_id),
    CONSTRAINT FK_user_id_User_BlogPost FOREIGN KEY (user_id) REFERENCES User (user_id) ON DELETE RESTRICT ON UPDATE RESTRICT
);";

$table_blogpost_likes = "
CREATE TABLE IF NOT EXISTS BlogPostLikes (
    post_id int,
    user_id int,
    CONSTRAINT PK_blogpost_post_id_user_id PRIMARY KEY (post_id, user_id),
    CONSTRAINT FK_post_id_BlogPost_BlogPostLikes FOREIGN KEY (post_id) REFERENCES BlogPost (post_id) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT FK_user_id_User_BlogPostLikes FOREIGN KEY (user_id) REFERENCES User (user_id) ON DELETE RESTRICT ON UPDATE RESTRICT
);";

$table_blogpost_reads = "
CREATE TABLE IF NOT EXISTS BlogPostReads (
    post_id int,
    user_id int,
    CONSTRAINT PK_blogpost_post_id_user_id PRIMARY KEY (post_id, user_id),
    CONSTRAINT FK_post_id_BlogPost_BlogPostReads FOREIGN KEY (post_id) REFERENCES BlogPost (post_id) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT FK_user_id_User_BlogPostReads FOREIGN KEY (user_id) REFERENCES User (user_id) ON DELETE RESTRICT ON UPDATE RESTRICT
);";

// TO-DO: Store password as hash
$insert_users = "
INSERT INTO
User (user_name, user_pass, user_full_name)
VALUES (:user_name, :user_pass, :user_full_name)";

$insert_user_followers = "
INSERT INTO
UserFollower (user_id, follower_id)
VALUES (:user_id, :follower_id)";


$insert_blogposts = "
INSERT INTO
BlogPost (user_id, post_title, post_body, post_public, post_date)
VALUES (:user_id, :post_title, :post_body, :post_public, :post_date)";

$insert_blogpostlikes = "
INSERT INTO
BlogPostLikes (post_id, user_id)
VALUES (:post_id, :user_id)";

$insert_blogpostreads = "
INSERT INTO
BlogPostReads (post_id, user_id)
VALUES (:post_id, :user_id)";

// Data users
$users = [
    [
        "user_name" => "user1",
        "user_pass" => "pass1",
        "user_full_name" => "User One"
    ],
    [
        "user_name" => "user2",
        "user_pass" => "pass2",
        "user_full_name" => "User Two"
    ],
    [
        "user_name" => "user3",
        "user_pass" => "pass3",
        "user_full_name" => "User Three"
    ],
];

// Data user followers
$userfollowers = [
    [
        "user_id" => 1,
        "follower_id" => 2
    ],
    [
        "user_id" => 1,
        "follower_id" => 3
    ],
    [
        "user_id" => 2,
        "follower_id" => 1
    ],
];

// Data blogposts
$blogs = [
    [
        "user_id" => 1,
        "post_title" => "My first blog post",
        "post_body" => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Deleniti aperiam provident libero quisquam velit. Libero illum blanditiis, rem minus expedita consequuntur iusto! Ex, earum magnam dignissimos alias expedita nostrum impedit asperiores corporis non eos! Ipsum, est consequatur? Deleniti rem culpa vitae nulla. Quaerat placeat necessitatibus dolore modi illo quae ab.",
        "post_public" => true,
        "post_date" => "2019-10-20" // YYYY-MM-DD
    ],
    [
        "user_id" => 2,
        "post_title" => "This is my second blog post",
        "post_body" => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Deleniti aperiam provident libero quisquam velit. Libero illum blanditiis, rem minus expedita consequuntur iusto! Ex, earum magnam dignissimos alias expedita nostrum impedit asperiores corporis non eos! Ipsum, est consequatur? Deleniti rem culpa vitae nulla. Quaerat placeat necessitatibus dolore modi illo quae ab.",
        "post_public" => true,
        "post_date" => "2020-10-10" // YYYY-MM-DD
    ],
    [
        "user_id" => 1,
        "post_title" => "My recent blog post, a bit longer",
        "post_body" => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Deleniti aperiam provident libero quisquam velit. Libero illum blanditiis, rem minus expedita consequuntur iusto! Ex, earum magnam dignissimos alias expedita nostrum impedit asperiores corporis non eos! Ipsum, est consequatur? Deleniti rem culpa vitae nulla. Quaerat placeat necessitatibus dolore modi illo quae ab. Lorem, ipsum dolor sit amet consectetur adipisicing elit. Voluptate consectetur, laudantium aspernatur ducimus autem quisquam praesentium molestiae esse illo et eligendi, veniam deserunt saepe obcaecati ea! Nostrum rerum eum quaerat iure, debitis libero eos! Adipisci ad vel amet, dolores id, at animi, voluptas veritatis accusamus repellat perspiciatis fuga! Dolorem, numquam!",
        "post_public" => true,
        "post_date" => "2021-09-21" // YYYY-MM-DD
    ]
];

// Data blogpost likes
$blogslikes = [
    [
        "post_id" => 1,
        "user_id" => 2
    ],
    [
        "post_id" => 1,
        "user_id" => 3
    ],
    [
        "post_id" => 2,
        "user_id" => 1
    ],
    [
        "post_id" => 2,
        "user_id" => 3
    ]
];

// Data blogpost reads
$blogsreads = [
    [
        "post_id" => 1,
        "user_id" => 2
    ],
    [
        "post_id" => 1,
        "user_id" => 3
    ],
    [
        "post_id" => 2,
        "user_id" => 1
    ]
];

try {
    $db_connection = new PDO("mysql:host=localhost", DB_USER, DB_PASS);

    // Create database
    $db_connection->exec($db_blog_host);

    $db_connection = new PDO("mysql:host=localhost;dbname=" . DB_NAME, DB_USER, DB_PASS);

    // Create tables
    $db_connection->exec($table_user);
    $db_connection->exec($table_user_follower);
    $db_connection->exec($table_blogpost);
    $db_connection->exec($table_blogpost_likes);
    $db_connection->exec($table_blogpost_reads);

    //------------------------------------------------------
    //   Insert data for users
    //------------------------------------------------------

    $user_name = "";
    $user_pass = "";
    $user_full_name = "";

    $statment = $db_connection->prepare($insert_users);
    $statment->bindParam(":user_name", $user_name);
    $statment->bindParam(":user_pass", $user_pass);
    $statment->bindParam(":user_full_name", $user_full_name);

    foreach($users as $user) {
        $user_name = $user['user_name'];
        $user_pass = $user['user_pass'];
        $user_pass = password_hash($user_pass, PASSWORD_DEFAULT);
        $user_full_name = $user['user_full_name'];

        $statment->execute();
    }

    //------------------------------------------------------
    //   Insert data for user followers
    //------------------------------------------------------

    $user_id = "";
    $follower_id = "";

    $statment = $db_connection->prepare($insert_user_followers);
    $statment->bindParam(":user_id", $user_id);
    $statment->bindParam(":follower_id", $follower_id);

    foreach($userfollowers as $userfollower) {
        $user_id = $userfollower['user_id'];
        $follower_id = $userfollower['follower_id'];
        $statment->execute();
    }

    //------------------------------------------------------
    //   Insert data for blogposts
    //------------------------------------------------------

    $user_id = "";
    $blog_post_title = "";
    $blog_post_body = "";
    $post_public = true;
    $blog_post_date = "";

    $statment = $db_connection->prepare($insert_blogposts);
    $statment->bindParam(":user_id", $user_id);
    $statment->bindParam(":post_title", $blog_post_title);
    $statment->bindParam(":post_body", $blog_post_body);
    $statment->bindParam(":post_public", $blog_post_public);
    $statment->bindParam(":post_date", $blog_post_date);

    foreach($blogs as $blog) {
        $user_id = $blog['user_id'];
        $blog_post_title = $blog['post_title'];
        $blog_post_body = $blog['post_body'];
        $blog_post_public = $blog['post_public'];
        $blog_post_date = $blog['post_date'];

        $statment->execute();
    }

    //------------------------------------------------------
    //   Insert data for blogpost likes
    //------------------------------------------------------

    $user_id = "";
    $post_id = "";

    $statment = $db_connection->prepare($insert_blogpostlikes);
    $statment->bindParam(":user_id", $user_id);
    $statment->bindParam(":post_id", $post_id);

    foreach($blogslikes as $blogslike) {
        $user_id = $blogslike['user_id'];
        $post_id = $blogslike['post_id'];
        $statment->execute();
    }

    //------------------------------------------------------
    //   Insert data for blogpost reads
    //------------------------------------------------------

    $user_id = "";
    $post_id = "";

    $statment = $db_connection->prepare($insert_blogpostreads);
    $statment->bindParam(":user_id", $user_id);
    $statment->bindParam(":post_id", $post_id);

    foreach($blogsreads as $blogsread) {
        $post_id = $blogsread['post_id'];
        $user_id = $blogsread['user_id'];
        $statment->execute();
    }

    $success = true;
}
catch (PDOException $e) {
    var_dump($e);
    $success = false;
}

$message = $success?
"Congratulations: instalation is successful":
"Oops: instalation is unsuccessful";
?>

<h1><?=$message?></h1>

