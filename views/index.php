<?php
session_start();
require_once '../src/Repositories/ArticleRepository.php';
require_once '../src/Repositories/UserRepository.php';
require_once '../helpers/helpers.php';

use src\Repositories\UserRepository;
use src\Repositories\ArticleRepository;

if (isset($_SESSION['userId'])) {
    $user = (new UserRepository())->getUserById($_SESSION['userId']);
}
$articleRepository = new ArticleRepository();
$articles = $articleRepository->getAllArticles();

?>
<?php require_once '../layout/header.php'; ?>
<div class="row justify-content-center ">
    <div class="col-10 col-md-8 col-lg-10">
        <div class="card bg-white">
            <div class="card-body ">
                <ol class="list-group">
                    <?php if (!isset($_SESSION['userId'])) { ?>
                        <?php foreach ($articles as $article) { ?>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto  ">
                                    <div class="fw-bold me-4"><a href=<?php echo $article->url ?> target="_blank"><?php echo $article->title ?></a></div>
                                    <div class="my-2 ">
                                        <div class="d-flex align-items-center ">
                                            <img class=" nav-pic me-3" src="<?php echo ($articleRepository->getArticleAuthor($article->id))->profile_picture ?>" alt="<?php echo ($articleRepository->getArticleAuthor($article->id))->name ?>">
                                            <div><small class="fw">Posted by: <?php echo ($articleRepository->getArticleAuthor($article->id))->name ?></small></div>
                                        </div>
                                        <div class="ms-2 me-auto text-secondary">
                                            <div><small class="fw">Created at: <?php echo formetDate($article->created_at) ?></small></div>
                                            <?php if ($article->updated_at !== null) { ?>
                                                <div><small class="fw">Updated at: <?php echo formetDate($article->updated_at) ?></small></div><?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php } ?>
                    <?php } ?>
                    <?php if (isset($_SESSION['userId'])) { ?>
                        <?php foreach ($articles as $article) { ?>
                            <?php if ($article->author_id === $_SESSION['userId']) { ?>

                                <li class="list-group-item d-flex justify-content-between align-items-start">

                                    <div class="ms-2 me-auto  ">
                                        <div class="fw-bold me-4"><a href=<?php echo $article->url ?> target="_blank"><?php echo $article->title ?></a></div>
                                        <div class="my-2 ">
                                            <div class="d-flex align-items-center ">
                                                <img class=" nav-pic me-3" src="<?php echo ($articleRepository->getArticleAuthor($article->id))->profile_picture ?>" alt="<?php echo ($articleRepository->getArticleAuthor($article->id))->name ?>">
                                                <div><small class="fw">Posted by: <?php echo ($articleRepository->getArticleAuthor($article->id))->name ?></small></div>
                                            </div>
                                            <div class="ms-2 me-auto text-secondary">
                                                <div><small class="fw">Created at: <?php echo formetDate($article->created_at) ?></small></div>
                                                <?php if ($article->updated_at !== null) { ?>
                                                    <div><small class="fw">Updated at: <?php echo formetDate($article->updated_at) ?></small></div><?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=" align-items-center">
                                        <div class="text-center my-2"> <a href="update_article.php?articleId=<?php echo $article->id; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
                                                    <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z" />
                                                </svg></a>
                                        </div>
                                        <div class="text-center"><a href="../controllers/delete_article.php?articleId=<?php echo $article->id; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-trash3 text-danger" viewBox="0 0 16 16">
                                                    <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z" />
                                                </svg></a>
                                        </div>
                                    </div>
                                </li>
                            <?php } ?>
                        <?php } ?>
                        <?php foreach ($articles as $article) { ?>
                            <?php if ($article->author_id !== $_SESSION['userId']) { ?>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto  ">
                                        <div class="fw-bold me-4"><a href=<?php echo $article->url ?> target="_blank"><?php echo $article->title ?></a></div>
                                        <div class="my-2 ">
                                            <div class="d-flex align-items-center ">
                                                <img class=" nav-pic me-3" src="<?php echo ($articleRepository->getArticleAuthor($article->id))->profile_picture ?>" alt="<?php echo ($articleRepository->getArticleAuthor($article->id))->name ?>">
                                                <div><small class="fw">Posted by: <?php echo ($articleRepository->getArticleAuthor($article->id))->name ?></small></div>
                                            </div>
                                            <div class="ms-2 me-auto text-secondary">
                                                <div><small class="fw">Created at: <?php echo formetDate($article->created_at) ?></small></div>
                                                <?php if ($article->updated_at !== null) { ?>
                                                    <div><small class="fw">Updated at: <?php echo formetDate($article->updated_at) ?></small></div><?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </ol>
            </div>
        </div>
    </div>
</div>
<?php require_once '../layout/footer.php' ?>justify-content-center