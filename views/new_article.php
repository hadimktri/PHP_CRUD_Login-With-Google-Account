<?php
session_start();
require_once '../src/Repositories/ArticleRepository.php';
require_once '../src/Repositories/UserRepository.php';
require_once '../helpers/helpers.php';

use src\Repositories\UserRepository;
use src\Repositories\ArticleRepository;

if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
    exit;
} else {
    $userId = $_SESSION['userId'];
    $user = (new UserRepository())->getUserById($_SESSION['userId']);
}
if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    if (empty($_POST['title'])) {
        $_SESSION['title_error'] = 'Please enter the title.';
    }
    if (empty($_POST['url']) || !validUrl($_POST['url'])) {
        $_SESSION['url_error'] = 'Please enter a valid url.';
    }
    if (isset($_POST['submit']) && !empty($_POST['title']) && !empty($_POST['url']) && validUrl($_POST['url'])) {
        (new ArticleRepository())->saveArticle($_POST['title'], sanitizeUrl($_POST['url']), $userId);
        header("Location:index.php");
        exit;
    }
}
?>
<?php require_once '../layout/header.php' ?>
<div class="container">
    <div class="container row d-flex justify-content-center">
        <div class="col-8 col-md-8 col-lg-10">
            <div class="card">
                <div class=" card-body p-5">
                    <form action="#" method="POST">
                        <fieldset>
                            <legend>Fill required fields below</legend>
                            <span class="text-danger">
                                <?php if (isset($_SESSION['title_error'])) {
                                    echo $_SESSION['title_error'];
                                    unset($_SESSION['title_error']);
                                } ?>
                            </span>
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" id="title" class="form-control" name="title" placeholder="Enter the new title">
                            </div>
                            <span class="text-danger">
                                <?php if (isset($_SESSION['url_error'])) {
                                    echo $_SESSION['url_error'];
                                    unset($_SESSION['url_error']);
                                } ?>
                            </span>
                            <div class="mb-3">
                                <label for="url" class="form-label">URL</label>
                                <input type="text" id="url" class="form-control" name="url" placeholder="Enter the new url">
                            </div>
                            <div class="mb-3 d-grid  mx-5">
                                <button type="submit" class="btn btn-secondary" name="submit">Submit</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once '../layout/footer.php' ?>