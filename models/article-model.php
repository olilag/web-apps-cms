<?php

class ArticleModel
{
    private array $config;
    private $db;
    public function __construct()
    {
        include 'db_config.php';
        $this->config = $db_config;
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    }

    public function dbConnect()
    {
        $this->db = new mysqli($this->config['server'], $this->config['login'], $this->config['password'], $this->config['database'], $this->config['port']);
    }

    public function dbClose()
    {
        $this->db->close();
    }

    public function getArticle(int $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $query_result = $stmt->get_result();
        return $query_result->fetch_assoc();
    }

    public function getArticles()
    {
        $stmt = $this->db->prepare("SELECT * FROM articles");
        $stmt->execute();
        $query_result = $stmt->get_result();
        return $query_result;
    }

    public function saveArticle(int $id, $new_name, $new_data)
    {
        $stmt = $this->db->prepare("UPDATE articles SET name = ?, content = ? WHERE id = ?");
        $stmt->bind_param('ssi', $new_name, $new_data, $id);
        $stmt->execute();
    }

    public function newArticle($name)
    {
        $stmt = $this->db->prepare("INSERT INTO articles (name) VALUES (?)");
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $last_id = $this->db->insert_id;
        return $last_id;
    }

    public function deleteArticle(int $id)
    {
        $stmt = $this->db->prepare("DELETE FROM articles WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }

    public function addLike(int $id)
    {
        $stmt = $this->db->prepare("UPDATE articles SET likes = likes + 1 WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }

    public function addDislike(int $id)
    {
        $stmt = $this->db->prepare("UPDATE articles SET dislikes = dislikes + 1 WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }
}
