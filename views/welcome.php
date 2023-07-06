<?php
session_start();
require_once '../src/Repositories/UserRepository.php';

use src\Repositories\UserRepository;

if (!isset($_SESSION['userId'])) {
	header('Location: login.php');
	exit;
} else {
	$user = (new UserRepository())->getUserById($_SESSION['userId']);
}
?>
<?php require_once '../layout/header.php'; ?>
<div class="container m-5 pl-20 p-5">
	<h1 class="display-1"><?php echo "Hello" . ' ' . $user->name  ?> </h1>
	<h2 class="display-6 mt-4 text-secondary">We're thrilled to see you here! </h2>
	<blockquote cite="https://www.goodreads.com/quotes/131926-the-miracle-is-this---the-more-we-share-the">
		<p class="h4 text-secondary">"The miracle is this: The more we share the more we have." —Leonard Nimoy.</p>
	</blockquote>
</div>
<?php require_once '../layout/footer.php' ?>