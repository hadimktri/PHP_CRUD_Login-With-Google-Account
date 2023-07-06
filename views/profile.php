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
<div class="my-2 d-flex justify-content-center align-items-center">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-8 col-md-8 col-lg-10">
                <div class="card bg-white">
                    <div class="card-body p-5">
                        <img class=" profile-pic my-2" src="<?php echo $user->profile_picture ?>" alt="<?php echo $user->profile_picture ?>">
                        <div class="card-body">
                            <h5 class="card-title">Profile</h5>
                            <p class="card-text"></p>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><?php echo $user->name ?></li>
                            <li class="list-group-item"><?php echo $user->email ?></li>
                        </ul>
                        <div class="card-body">
                            <a href="settings.php" class="card-link">Edit Your Profile</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once '../layout/footer.php' ?>