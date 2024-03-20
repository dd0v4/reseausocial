<?php
class Post {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function savePost($title, $content, $author) {
        $statement = $this->pdo->prepare("INSERT INTO posts (title, content, author) VALUES (:title, :content, :author)");
        $statement->bindParam(':title', $title);
        $statement->bindParam(':content', $content);
        $statement->bindParam(':author', $author);

        return $statement->execute();
    }

    public function updatePost($id, $title, $content, $author) {
        $statement = $this->pdo->prepare("UPDATE posts SET title = :title, content = :content WHERE id = :id AND author = :author");
        $statement->bindParam(':title', $title);
        $statement->bindParam(':content', $content);
        $statement->bindParam(':id', $id);
        $statement->bindParam(':author', $author);

        return $statement->execute();
    }

    public function deletePost($id, $author) {
        $statement = $this->pdo->prepare("DELETE FROM posts WHERE id = :id AND author = :author");
        $statement->bindParam(':id', $id);
        $statement->bindParam(':author', $author);

        return $statement->execute();
    }
}
