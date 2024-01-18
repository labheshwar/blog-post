<?php
require_once 'utilities/blogposts.php';
require_once 'utilities/user.php';

	if (!is_user_loggedin()) {
		header("Location: index.php");
		return;
	}

	if(isset($_GET['id'])) {
		$post_id = $_GET['id'];
	}

	$users = get_users_who_like($post_id);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<link rel="stylesheet" href="style.css">
		<title>Home</title>
	</head>
	<body>
		
		<?php include "header.php";?>

		<div style="text-align: center">
			<h1>User details who like the post</h1>
			<?php
			if ($users != null):
				foreach($users as $user):
					$user_name = $user["user_full_name"];
			?>
			<div class="username"><?=$user_name?></div>
			<?php
			endforeach;
			endif;
			?>
		</div>
	</body>
</html>
