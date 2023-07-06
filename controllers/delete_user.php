<?php
session_start();
require_once '../src/Repositories/UserRepository.php';
require_once '../src/Repositories/ArticleRepository.php';

use src\Repositories\ArticleRepository;
use src\Repositories\UserRepository;
$user=(new UserRepository())->getUserById($_SESSION['userId']);

(new ArticleRepository())->deleteArticleByAuthor($_SESSION['userId']);
unlink(strval($user->profile_picture));
(new UserRepository())->deleteUserById($_SESSION['userId']);
header("Location: ../views/login.php");
session_destroy();
exit;
