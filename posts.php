<?php
require "db.php";
require "Post.php";
session_start();

$error = null;

if (!isset($_SESSION["connected"]) || !$_SESSION["connected"]) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION["connectedAs"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $content = $_POST["content"];
    $post = new Post($pdo);
    $success = $post->savePost($title, $content, $username);

    if ($success) {
        header("Location: home.php");
        exit();
    } else {
        $error = "Error";
    }
}
try{
    $query = $pdo->query("SELECT * FROM posts");
    $posts = $query->fetchAll();
}catch(PDOException $e){
    $error = $e->getMessage();
}

?>
<head>
    <link rel="stylesheet" href="./assets/css/styles.css">
    <title>Home</title>
</head>
<body>
    <div class="home">
        <h1>Posts</h1>
        <a href="logout.php">Logout</a>
        <?php if ($error) : ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php foreach($posts as $post): ?>
            <div class="post">
                <p>Author : <?php echo $post["author"]; ?></p>
                <p>Title : <?php echo $post["title"]; ?></p>
                <p>Content : <?php echo $post["content"]; ?></p>
                <p>Created at : <?php echo $post["created_at"]; ?></p>
            </div>
        <?php endforeach; ?>

    </div>
</body>