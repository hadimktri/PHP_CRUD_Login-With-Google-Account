<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
	<link rel="stylesheet" href="../css/styles.css">
	<title>Assignment 2</title>
</head>

<body class="wrapper">
	<nav class="navbar navbar-expand-lg  fixed-top px-5 py-0">
		<div class="container">
			<a class="navbar-brand text-warning" href="../index.php">Posts</a>
			<button class="navbar-toggler px-2 pt-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-text-indent-right  text-light" viewBox="0 0 16 16">
					<path d="M2 3.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5zm10.646 2.146a.5.5 0 0 1 .708.708L11.707 8l1.647 1.646a.5.5 0 0 1-.708.708l-2-2a.5.5 0 0 1 0-.708l2-2zM2 6.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z" />
				</svg>
			</button>
			<div class="collapse navbar-collapse" id="navbarNavAltMarkup">
				<div class="navbar-nav">
					<?php if (isset($_SESSION["userId"])) : ?>
						<a class="nav-link p-2 text-light" aria-current="page" href="../views/new_article.php">Add Post</a>
						<a class="nav-link p-2 text-light" href="../views/edit_article.php">Edit Post</a>
					<?php endif; ?>
					<?php if (!isset($_SESSION["userId"])) : ?>
						<a class="nav-link p-2 text-light" href="../views/login.php">Log In</a>
						<a class="nav-link p-2 text-light" href="../views/register.php">Sign Up</a>
					<?php endif; ?>
					<?php if (isset($_SESSION["userId"])) : ?>
						<a class="nav-link p-2 text-light" href="../controllers/logout.php">Log Out</a>
						<a class="nav-link p-2 text-light" href="../views/settings.php">Edit Profile</a>
						<a class="nav-link  p-2 user-avatar" href="../views/profile.php"><img class=" nav-pic" src="<?php echo $user->profile_picture ?>"> </a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</nav>
	<div id='main'>