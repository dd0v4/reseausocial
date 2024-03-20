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
    $query = $pdo->prepare("SELECT * FROM posts WHERE author = :author");
    $query->execute([
        "author" => $username
    ]);
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
        <h1>Home</h1>
        <p>Welcome, <?= $username ?></p>
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
                <a href="edit.php?id=<?=htmlentities($post['id'])?>">Edit</a>
                <a href="delete.php?id=<?=htmlentities($post['id'])?>">Delete</a>
            </div>
        <?php endforeach; ?>

        <form action="" method="post" class="formgroup">
            <label for="title">Title</label>
            <input type="text" id="title" name="title">
            <label for="content">Content</label>
            <textarea name="content" id="content" cols="30" rows="10"></textarea>
            <input type="hidden" name="author" value="<?= htmlentities($username) ?>">
            <button type="submit">Post</button>
        </form>
    </div>
</body>