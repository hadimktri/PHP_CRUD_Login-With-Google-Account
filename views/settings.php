<?php
session_start();
require_once '../src/Repositories/UserRepository.php';
require_once '../helpers/helpers.php';

use src\Repositories\UserRepository;

if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
    exit;
} else {
    $user = (new UserRepository())->getUserById($_SESSION['userId']);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_FILES['profile'])) {
            if (empty($_FILES['profile']['name'])) {
                $_SESSION['file_error'] = 'Please choose a file';
            } else {
                $allowed = ['jpg', 'jpeg', 'gif', 'png'];
                $file_name = $_FILES['profile']['name'];
                $nameArray = explode('.', $_FILES['profile']['name']);
                $file_extn = strtolower(end($nameArray));
                $filePath = "../images/profile/" . substr(md5(time()), 0, 10) . '.' . $file_extn;

                if (!in_array($file_extn, $allowed)) {
                    $_SESSION['file_error'] = 'incorrect file type. Allowd: ' . implode(', ', $allowed);
                } elseif (($_FILES['profile']['size'] > 4000000)) {
                    $_SESSION['file_error'] = 'Image size exceeds 2MB';
                } elseif (!$_FILES['profile']['error']) {
                    unlink(strval($user->profile_picture));
                    move_uploaded_file($_FILES['profile']['tmp_name'], $filePath);
                    (new UserRepository())->updateProfilePic($_SESSION['userId'], $filePath);
                    header('Location: profile.php');
                    exit;
                }
            }
        }
        if (isset($_POST['subInfo'])) {
            if (empty($_POST['name']) || empty($_POST['email']) || !validEmail($_POST['email'])) {
                $_SESSION['email_error'] = "Please insert your name and email";
            } else {
                (new UserRepository())->updateUser($_SESSION['userId'], $_POST['name'], sanitizeEmail($_POST['email']));
                header('Location: profile.php');
                exit();
            }
        }
        if (isset($_REQUEST['subPass'])) {
            if (empty($_POST['password']) || empty($_POST['passConfirm'])) {
                $_SESSION['password_error'] = "please enter your Password and confirm it";
            } elseif ($_POST['password'] !== $_POST['passConfirm']) {
                $_SESSION['password_error'] = "Password doesn't match";
            } elseif (!validPassword($_POST['password'])) {
                $_SESSION['password_error'] = "Please insert a valid pasword";
            } else {
                $bcryptPasswordDigest = password_hash($_POST['password'], PASSWORD_BCRYPT,  ['cost' => 12]);
                (new UserRepository())->updatePassword($_SESSION['userId'], $bcryptPasswordDigest);
                header('Location: profile.php');
                exit();
            }
        }
    }
}
?>
<?php require_once '../layout/header.php' ?>
<div class="container">
    <div class="my-2 d-flex justify-content-center align-items-center">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card bg-white">
                        <div class="card-body px-5">
                            <div class="container my-3">
                                <form class="md-1" action="" method="post" enctype="multipart/form-data">
                                    <h5 class="font-bold ">Choose Profile picture</h5>
                                    <img class=" profile-pic my-2" src="<?php echo $user->profile_picture ?>" alt="<?php echo $user->profile_picture ?>">
                                    <div class=" d-flex  mt-3">
                                        <input type="file" name="profile" class="form-control form-control-sm me-4" id="formFileSm">
                                        <button name='subProfile' class="btn btn-secondary btn-sm" type="submit">Submit</button>
                                    </div>
                                    <span class="text-danger ">
                                        <?php if (isset($_SESSION['file_error'])) {
                                            echo $_SESSION['file_error'];
                                            unset($_SESSION['file_error']);
                                        } ?>
                                    </span>
                                </form>
                                <form class="md-1" action="" method="post">
                                    <h5 class="font-bold mt-3">Edit Your Info</h5>
                                    <span class="text-danger">
                                        <?php if (isset($_SESSION['email_error'])) {
                                            echo $_SESSION['email_error'];
                                            unset($_SESSION['email_error']);
                                        } ?>
                                    </span>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" name="name" class="form-control" id="name" value="<?php echo $user->name ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email address</label>
                                        <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" value="<?php echo $user->email ?>" placeholder="name@example.com">
                                    </div>
                                    <div class=" d-grid">
                                        <button name='subInfo' class="btn btn-secondary btn-sm mx-3" type="submit">Submit</button>
                                    </div>
                                </form>
                                <form class="md-1" action="" method="post">
                                    <h5 class="font-bold mt-3">Edit Your Password</h5>
                                    <span class="text-danger">
                                        <?php if (isset($_SESSION['password_error'])) {
                                            echo $_SESSION['password_error'];
                                            unset($_SESSION['password_error']);
                                        } ?></span>
                                    <div class=" mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control" id="password" value="" placeholder="at least 8 characters with one symbol">
                                    </div>
                                    <div class="mb-3">
                                        <label for="passConfirm" class="form-label">Confirm Password</label>
                                        <input type="password" name="passConfirm" class="form-control" id="passConfirm" value="" required="">
                                    </div>
                                    <div class="d-grid">
                                        <button name='subPass' class="btn btn-secondary btn-sm mx-3" type="submit">Submit</button>
                                    </div>
                                    <div class="d-flex justify-content-end mt-4 ">
                                        <a class="text-danger" href="../controllers/delete_user.php">Delet Account</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once '../layout/footer.php' ?><span class="text-danger">