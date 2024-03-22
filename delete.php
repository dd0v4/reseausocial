<?php
require "db.php";
require "Post.php";
session_start();

if (!isset($_SESSION["connected"]) || !$_SESSION["connected"]) {
    header('Location: login.php');
    exit();
}

// Si notre utilisateur n'est pas connecté, on le redirige vers login

$username = $_SESSION["connectedAs"];
// On crée une variable username avec la valeur stockée dans la session


if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    $id = $_GET["id"];
    $post = new Post($pdo);
    $success = $post->deletePost($id, $username);

    if ($success) {
        header("Location: home.php");
        exit();
    } else {
        $error = "Error deleting post";
    }
} else {
    $error = "Invalid request";
}

// On utilise la méthode pour supprimer un post dans notre classe Post
echo $error; 
?>