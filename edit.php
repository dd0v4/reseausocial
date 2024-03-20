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
    $id = $_POST["id"];
    $title = $_POST["title"];
    $content = $_POST["content"];
    $post = new Post($pdo);
    $success = $post->updatePost($id, $title, $content, $username);

    if ($success) {
        header("Location: home.php");
        exit();
    } else {
        $error = "Error";
    }
}

try {
    $post_id = $_GET["id"];
    $query = $pdo->prepare("SELECT * FROM posts WHERE id = :id AND author = :author");
    $query->execute([
        "id" => $post_id,
        "author" => $username
    ]);
    $post = $query->fetch();
} catch (PDOException $e) {
    $error = $e->getMessage();
}

?>
<head>
    <link rel="stylesheet" href="./assets/css/styles.css">
    <title>Edit Post</title>
</head>
<body>
    <div class="edit-post">
        <h1>Edit Post</h1>
        <?php if ($error) : ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="" method="post" class="formgroup">
            <input type="hidden" name="id" value="<?= htmlentities($post['id']) ?>">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?= htmlentities($post['title']) ?>">
            <label for="content">Content</label>
            <textarea name="content" id="content" cols="30" rows="10"><?= htmlentities($post['content']) ?></textarea>
            <button type="submit">Save Changes</button>
        </form>
    </div>
</body>
