<?php
session_start();
require_once '../src/Repositories/ArticleRepository.php';

use src\Repositories\ArticleRepository;

(new ArticleRepository())->deleteArticle($_GET['articleId']);
header("Location: ../views/index.php");
exit;
