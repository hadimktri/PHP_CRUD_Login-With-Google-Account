<?php
session_start();

if (isset($_SESSION['userId'])) {
    unset($_SESSION['userId']);
}

require_once '../src/Repositories/UserRepository.php';
require_once '../helpers/helpers.php';
require '../google-api/vendor/autoload.php';

use src\Repositories\UserRepository;

$userRepository = new UserRepository();
$sqlConnection = (new UserRepository())->getMySqlConnection();
$client = new Google_Client();

$client->setClientId($_ENV['CLIENT_ID']);
$client->setClientSecret($_ENV['CLIENT_SECRET']);
$client->setRedirectUri($_ENV['REDIRECT_URIS']);
$client->addScope("email");
$client->addScope("profile");

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (!isset($token["error"])) {
        $client->setAccessToken($token['access_token']);
        $googleOauth = new Google_Service_Oauth2($client);

        $googleAccountInfo = $googleOauth->userinfo->get();
        $googleId = $googleAccountInfo->getId();
        $email = $googleAccountInfo->getEmail();
        $name = $googleAccountInfo->getGivenName();
        $url = $googleAccountInfo->getPicture();

        $getUserByEmail = $userRepository->getUserByEmail($email);
        $getUserByGId = $userRepository->getUserByGoogleId($googleId);

        if ($getUserByGId && $getUserByEmail) {
            $_SESSION['userId'] = $getUserByEmail->id;
            header('Location: welcome.php');
            exit;
        } elseif (!$getUserByGId && $getUserByEmail) {
            $userRepository->updateGoogleId($getUserByEmail->id, $googleId, $url);
            $_SESSION['userId'] = $getUserByEmail->id;
            header('Location: welcome.php');
            exit;
        } else {
            $profilePic = "../images/profile/" . substr(md5(time()), 0, 10) . '.png';
            file_put_contents($profilePic, file_get_contents($url));
            $googleUser = $userRepository->saveGoogleUser($googleId, $name, $email, $profilePic);
            if ($googleUser) {
                $_SESSION['userId'] = $googleUser->id;
                header('Location: welcome.php');
                exit;
            } else {
                echo "Sign up failed!(Something went wrong).";
            }
        }
    } else {
        header('Location: login.php');
        exit;
    }
}

$errors = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['email']) || !validEmail($_POST['email'])) {
        $_SESSION['error'] = 'Valid email and password required';
        $errors = true;
    } else {
        $_SESSION['email'] = $_POST['email'];
    }
    if (empty($_POST['password']) || !validPassword($_POST['password'])) {
        $_SESSION['password_error'] = 'Valid password required';
        $errors = true;
    }
    if ($errors) {
        $_SESSION['error'] = 'Valid email and password required';
        header('Location: login.php');
        exit;
    } elseif ((new UserRepository())->getUserByEmail($_POST['email']) === false) {
        $_SESSION['error'] = 'User not found';
    } else {
        $user = (new UserRepository())->getUserByEmail($_POST['email']);
        if (!password_verify($_POST['password'], $user->password_digest)) {
            $_SESSION['error'] = 'Valid email and password required';
        } else {
            $_SESSION['userId'] = $user->id;
            header('Location: welcome.php');
            exit;
        }
    }
}
?>
<?php require_once '../layout/header.php'; ?>
<div class="my-2 d-flex justify-content-center align-items-center">
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="col-8 col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-body p-5">
                        <form class="mb-3 mt-md-4" action="" method="post">
                            <h2 class="fw-bold mb-2 text-uppercase ">Hello</h2>
                            <p class=" mb-2">Please enter your email and password or Login with</p>
                            <div class="">
                                <div class="d-flex justify-content-center p-3">
                                    <a type="button" class="login-with-google-btn mx-3" href="<?php echo $client->createAuthUrl(); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-google" viewBox="0 0 16 16">
                                            <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z" />
                                        </svg>
                                    </a>
                                    <a type="button" class="login-with-google-btn mx-3" href="<?php echo $client->createAuthUrl(); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                                            <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                                        </svg>
                                    </a>
                                    <a type="button" class="login-with-google-btn mx-3" href="<?php echo $client->createAuthUrl(); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-twitter" viewBox="0 0 16 16">
                                            <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <span class=" text-danger">
                                <?php if (isset($_SESSION['error'])) {
                                    echo $_SESSION['error'];
                                    unset($_SESSION['error']);
                                } ?>
                            </span>
                            <div class="mb-3">
                                <label for="email" class="form-label ">Email address</label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" value="<?php echo isset($_SESSION['email']) ? ($_SESSION['email']) : '' ?>">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label ">Password</label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="" value="">
                            </div>
                            <p class="small"><a class="text-primary" href="#">Forgot password?</a></p>
                            <div class="d-grid mx-5">
                                <button class="btn btn-secondary " name="login" type="submit">Login</button>
                            </div>
                        </form>
                        <div>
                            <p class="my-1  text-center">Don't have an account? <a href="register.php" class="text-primary fw-bold">Sign Up</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once '../layout/footer.php' ?>