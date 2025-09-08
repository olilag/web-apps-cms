<?php

class ArticleView
{
    public function renderArticles($result)
    {
        include __DIR__.'/../templates/~header.php';
        include __DIR__.'/../templates/articles/index.php';
        include __DIR__.'/../templates/~footer.php';
    }

    public function renderArticle($result, $liked, $disliked)
    {
        include __DIR__.'/../templates/~header.php';
        include __DIR__.'/../templates/article/index.php';
        include __DIR__.'/../templates/~footer.php';
    }

    public function renderArticleEdit($result)
    {
        include __DIR__.'/../templates/~header.php';
        include __DIR__.'/../templates/article-edit/index.php';
        include __DIR__.'/../templates/~footer.php';
    }
}
