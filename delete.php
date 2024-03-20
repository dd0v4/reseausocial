<?php
require "db.php";
require "Post.php";
session_start();

if (!isset($_SESSION["connected"]) || !$_SESSION["connected"]) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION["connectedAs"];

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

echo $error; 
?>