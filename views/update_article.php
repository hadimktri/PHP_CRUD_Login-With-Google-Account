<?php
session_start();
require_once '../src/Repositories/UserRepository.php';
require_once '../src/Repositories/ArticleRepository.php';
require_once '../helpers/helpers.php';

use src\Repositories\UserRepository;
use src\Repositories\ArticleRepository;

if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
    exit;
} else {
    $user = (new UserRepository())->getUserById($_SESSION['userId']);
    $article = (new ArticleRepository())->getArticle($_GET['articleId']);
    if ($_SERVER["REQUEST_METHOD"] === 'POST') {
        if (empty($_POST['title'])) {
            $_SESSION['title_error'] = 'Please enter the title.';
        } elseif (empty($_POST['url']) || !validUrl($_POST['url'])) {
            $_SESSION['url_error'] = 'Please enter a valid url.';
        } else {
            (new ArticleRepository())->updateArticle($_GET['articleId'], trim($_POST['title']), sanitizeUrl($_POST['url']));
            header("Location:index.php");
            exit;
        }
    }
}
?>
<?php require_once '../layout/header.php'; ?>
<div class="container">
    <div class="my-2 d-flex justify-content-center align-items-center">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-md-9 col-lg-8">
                    <div class="card bg-white">
                        <div class="card-body p-5">
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
                                        <label for="title" class="form-label">TITLE</label>
                                        <input type="text" id="title" class="form-control" name="title" value="<?php echo $article->title; ?>">
                                    </div>
                                    <span class="text-danger">
                                        <?php if (isset($_SESSION['url_error'])) {
                                            echo $_SESSION['url_error'];
                                            unset($_SESSION['url_error']);
                                        } ?>
                                    </span>
                                    <div class="mb-3">
                                        <label for="url" class="form-label">URL</label>
                                        <input type="text" id="url" class="form-control" name="url" value="<?php echo $article->url; ?>">
                                    </div>
                                    <div class="d-grid px-5">
                                        <button type="submit" class="btn btn-secondary" name="submit">Submit</button>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once '../layout/footer.php' ?>