<?php

// suppress warning for line route() + 6
error_reporting(E_ERROR | E_PARSE);

require_once 'views/article-view.php';
require_once 'models/article-model.php';

class Controller
{
    private $view;
    private $model;
    private $root;

    public function __construct()
    {
        $this->view = new ArticleView();
        $this->model = new ArticleModel();
        $this->root = getenv("APP_ROOT");
    }

    private function fail($response_code, $msg)
    {
        http_response_code($response_code);
        exit($msg);
    }

    private function redirect($new_url)
    {
        header("Location: " . $this->root . $new_url, true, 303);
        exit();
    }

    private function route()
    {
        $target = filter_input(INPUT_GET, "page");
        if ($target === null) {
            $this->redirect('articles');
        }
        if (!preg_match("~^[-a-zA-Z0-9_/]+$~", $target)) {
            $this->fail(400, "Invalid page format");
        }
        $id = null;
        list($target, $id) = explode('/', $target, 2);
        switch ($target) {
            case 'articles':
                switch ($_SERVER['REQUEST_METHOD']) {
                    case 'GET':
                        return ['articles', null];
                    case 'POST':
                        $name = filter_input(INPUT_POST, 'name');
                        if ($name === null || !$name || strlen($name) > 32) {
                            $this->fail(400, 'Invalid article name');
                        }
                        return ['create', ['name' => $name]];
                    default:
                        header("Allow: GET, POST");
                        $this->fail(405, "");
                }
            case 'article':
                if ($id === null || !is_numeric($id)) {
                    $this->fail(400, "Invalid article id");
                }
                switch ($_SERVER['REQUEST_METHOD']) {
                    case 'GET':
                        return ['article', ['id' => $id]];
                    case 'POST':
                        $name = filter_input(INPUT_POST, 'name');
                        if ($name === null || !$name || strlen($name) > 32) {
                            $this->fail(400, 'Invalid article name');
                        }
                        $content = filter_input(INPUT_POST, 'content');
                        if ($content === null || $content === false || strlen($content) > 1024) {
                            $this->fail(400, 'Invalid article content');
                        }
                        return ['save', ['id' => $id, 'name' => $name, 'content' => $content]];
                    case 'DELETE':
                        return ['delete', ['id' => $id]];
                    default:
                        header("Allow: GET, POST, DELETE");
                        $this->fail(405, "");
                }
            case 'article-edit':
                if ($id === null || !is_numeric($id)) {
                    $this->fail(400, "Invalid article id");
                }
                switch ($_SERVER['REQUEST_METHOD']) {
                    case 'GET':
                        return ['article-edit', ['id' => $id]];
                    default:
                        header("Allow: GET");
                        $this->fail(405, "");
                }
            case 'like':
                if ($id === null || !is_numeric($id)) {
                    $this->fail(400, "Invalid article id");
                }
                switch ($_SERVER['REQUEST_METHOD']) {
                    case 'POST':
                        return ['like', ['id' => $id]];
                    default:
                        header("Allow: POST");
                        $this->fail(405, "");
                }
            case 'dislike':
                if ($id === null || !is_numeric($id)) {
                    $this->fail(400, "Invalid article id");
                }
                switch ($_SERVER['REQUEST_METHOD']) {
                    case 'POST':
                        return ['dislike', ['id' => $id]];
                    default:
                        header("Allow: POST");
                        $this->fail(405, "");
                }
            default:
                $this->fail(404, "");
        }
    }

    private function dispatch($action, $params = null)
    {
        switch ($action) {
            case 'articles':
                $this->model->dbConnect();
                $articles = $this->model->getArticles();
                $this->model->dbClose();
                $this->view->renderArticles($articles);
                break;
            case 'article':
                $this->model->dbConnect();
                $article = $this->model->getArticle($params['id']);
                $this->model->dbClose();
                if ($article === null) {
                    $this->fail(404, "");
                }
                if (!isset($_SESSION['liked'][$params['id']])) {
                    $_SESSION['liked'][$params['id']] = false;
                }
                if (!isset($_SESSION['disliked'][$params['id']])) {
                    $_SESSION['disliked'][$params['id']] = false;
                }
                $this->view->renderArticle($article, $_SESSION['liked'][$params['id']], $_SESSION['disliked'][$params['id']]);
                break;
            case 'article-edit':
                $this->model->dbConnect();
                $article = $this->model->getArticle($params['id']);
                $this->model->dbClose();
                if ($article === null) {
                    $this->fail(404, "");
                }
                $this->view->renderArticleEdit($article);
                break;
            case 'save':
                $this->model->dbConnect();
                $article = $this->model->getArticle($params['id']);
                if ($article === null) {
                    $this->fail(404, "");
                }
                $this->model->saveArticle($params['id'], $params['name'], $params['content']);
                $this->model->dbClose();
                $this->redirect('articles');
                break;
            case 'delete':
                $this->model->dbConnect();
                $article = $this->model->getArticle($params['id']);
                if ($article === null) {
                    $this->fail(404, "");
                }
                $this->model->deleteArticle($params['id']);
                $this->model->dbClose();
                http_response_code(204);
                break;
            case 'create':
                $this->model->dbConnect();
                $id = $this->model->newArticle($params['name']);
                $this->model->dbClose();
                $this->redirect("article-edit/$id");
                break;
            case 'like':
                $this->model->dbConnect();
                $article = $this->model->getArticle($params['id']);
                if ($article === null) {
                    $this->fail(404, "");
                }
                if (!isset($_SESSION['liked'][$params['id']])) {
                    $_SESSION['liked'][$params['id']] = false;
                }
                if ($_SESSION['liked'][$params['id']]) {
                    $this->fail(400, "Article already liked");
                }
                $this->model->addLike($params['id']);
                $this->model->dbClose();
                $_SESSION['liked'][$params['id']] = true;
                break;
            case 'dislike':
                $this->model->dbConnect();
                $article = $this->model->getArticle($params['id']);
                if ($article === null) {
                    $this->fail(404, "");
                }
                if (!isset($_SESSION['disliked'][$params['id']])) {
                    $_SESSION['disliked'][$params['id']] = false;
                }
                if ($_SESSION['disliked'][$params['id']]) {
                    $this->fail(400, "Article already disliked");
                }
                $this->model->addDislike($params['id']);
                $this->model->dbClose();
                $_SESSION['disliked'][$params['id']] = true;
                break;
            default:
                $this->fail(404, "");
                break;
        }
    }

    public function startup()
    {
        session_start();
        if (!isset($_SESSION['liked'])) {
            $_SESSION['liked'] = [];
        }
        if (!isset($_SESSION['disliked'])) {
            $_SESSION['disliked'] = [];
        }
        list($target, $params) = $this->route();
        error_log("$target");
        try {
            $this->dispatch($target, $params);
        } catch (Exception $e) {
            $this->fail(500, $e->getMessage());
        }
    }
}

$ctrl = new Controller();
$ctrl->startup();
