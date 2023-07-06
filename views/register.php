<?php
session_start();
require_once '../src/Repositories/UserRepository.php';
require_once '../helpers/helpers.php';

use src\Repositories\UserRepository;

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
	$errors = false;
	if (empty($_POST['name'])) {
		$_SESSION['name_error'] = 'Please enter your name.';
		$errors = true;
	} else {
		$_SESSION['name'] = $_POST['name'];
	}
	if (empty($_POST['email']) || !validEmail($_POST['email'])) {
		$_SESSION['email_error'] = 'Please enter a valid email.';
		$errors = true;
	} elseif ((new UserRepository())->getUserByEmail($_POST['email'])) {
		$_SESSION['name_error'] = 'You are already registered with this email.';
		$errors = true;
	} else {
		$_SESSION['email'] = $_POST['email'];
	}
	if (empty($_POST['password']) || !validPassword($_POST['password'])) {
		$_SESSION['password_error'] = 'Please enter a valid password.';
		$errors = true;
	}
	if ($_POST['password'] !== $_POST['passConfirm']) {
		$_SESSION['passConfirm_error'] = "Password doesn't match.";
		$errors = true;
	}
	$filePath = '../images/profile/avatar.jpg';
	if (isset($_FILES['profile'])) {
		if (!empty($_FILES['profile']['name'])) {
			$allowed = ['jpg', 'jpeg', 'gif', 'png', 'webp'];
			$file_name = $_FILES['profile']['name'];
			$nameArray = explode('.', $_FILES['profile']['name']);
			$file_extn = strtolower(end($nameArray));
			$filePath = "../images/profile/" . substr(md5(time()), 0, 10) . '.' . $file_extn;

			if (!in_array($file_extn, $allowed)) {
				$_SESSION['file_error'] = 'incorrect file type. Allowd: ' . implode(', ', $allowed);
				$errors = true;
			} elseif (($_FILES['profile']['size'] > 2097152)) {
				$_SESSION['file_error'] = 'Image size exceeds 2MB';
				$errors = true;
			}
		}
	}
	if (!$errors) {
		var_dump($filePath);
		move_uploaded_file($_FILES['profile']['tmp_name'], $filePath);
		$bcryptPasswordDigest = password_hash(($_POST['password']), PASSWORD_BCRYPT,  ['cost' => 12]);
		(new UserRepository())->saveUser($_POST['email'], ucwords($_POST['name']),  $bcryptPasswordDigest, $filePath);
		session_destroy();
		header('Location: login.php');
		exit;
	}
}
?>
<?php require_once '../layout/header.php'; ?>
<div class=" d-flex justify-content-center align-items-center">
	<div class="container  mb-2">
		<div class="row d-flex justify-content-center">
			<div class="col-12 col-md-8 col-lg-6">
				<div class="card bg-white">
					<div class="card-body p-5">
						<form class="md-1" action="#" method="post" enctype="multipart/form-data">
							<h4 class="fw-bold mb-2 ">Insert Your Info</h4>
							<p class="mt-1  text-center">Have an account? <a href="login.php" class="text-primary fw-bold">Sign in</a></p>
							<p class=" mb-4">Please enter your email and password !</p>
							<span class="text-danger">
								<?php if (isset($_SESSION['name_error'])) {
									echo $_SESSION['name_error'];
									unset($_SESSION['name_error']);
								} ?>
							</span>
							<div class="mb-3">
								<label for="name" class="form-label ">Name*</label>
								<input type="text" class="form-control" id="name" name="name" placeholder="" value="">
							</div>
							<span class="text-danger">
								<?php if (isset($_SESSION['email_error'])) {
									echo $_SESSION['email_error'];
									unset($_SESSION['email_error']);
								} ?>
							</span>
							<div class="mb-3">
								<label for="email" class="form-label ">Email address*</label>
								<input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" value=''>
							</div>
							<span class="text-danger">
								<?php if (isset($_SESSION['password_error'])) {
									echo $_SESSION['password_error'];
									unset($_SESSION['password_error']);
								} ?>
							</span>
							<div class="mb-3">
								<label for="password" class="form-label ">Password*</label>
								<input type="password" class="form-control" id="password" name="password" placeholder="at least 8 characters with one symbol">
							</div>
							<span class="text-danger">
								<?php if (isset($_SESSION['passConfirm_error'])) {
									echo $_SESSION['passConfirm_error'];
									unset($_SESSION['passConfirm_error']);
								} ?>
							</span>
							<div class="mb-3">
								<label for="passConfirm" class="form-label ">Confirm Password*</label>
								<input type="password" class="form-control" id="passConfirm" name="passConfirm" placeholder="">
							</div>
							<span class="text-danger">
								<?php if (isset($_SESSION['file_error'])) {
									echo $_SESSION['file_error'];
									unset($_SESSION['file_error']);
								} ?>
							</span>
							<div class="mb-3">
								<label for="formFileSm" class="form-label">Choose Profile picture</label>
								<input type="file" name="profile" class="form-control form-control-sm " id="formFileSm">
							</div>
							<div class="d-grid mx-5">
								<button class="btn btn-secondary" type="submit">Sign Up</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php require_once '../layout/footer.php' ?>