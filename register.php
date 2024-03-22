<?php

require "db.php";
require "User.php";

$error = null;

try {
    if (isset($_POST["username"], $_POST["password"], $_POST["passwordConfirm"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $passwordConfirm = $_POST["passwordConfirm"];
        // Si l'user a entré son pseudo et son mot de passe, on les stocke dans des variables

        $user = new User($username, $password, $passwordConfirm, $pdo);
        $registerResult = $user->registerUser();
        // Avec nos variables précédemment créées, on crée un nouvel objet de classe User avec ces variables comme propriété, et on utilise notre fonction register

        if ($registerResult === true) {
            echo htmlentities($username) . " successfully registered.";
            exit;
        } else {
            $error = $registerResult;
        }
        // Si notre fonction register a retourné true, on echo que notre user a bien été registered
    }
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
    // Si erreur , on la stocke pour l'afficher dans la page
?>

<head>
    <link rel="stylesheet" href="./assets/css/styles.css">
    <title>Register</title>
</head>
<div class="container">
    <form action="" method="post">
        <h1>Register</h1>
        <div class="formgroup">
            <p>Already registered ? <a href="login.php">Login</a></p>
            <label for="username">Username</label>
            <input type="text" id="username" name="username">
            <label for="password">Password</label>
            <input type="password" id="password" name="password">
            <label for="passwordConfirm">Confirm password</label>
            <input type="password" id="passwordConfirm" name="passwordConfirm">
            <button type="submit">Register</button>
            <?php if ($error) : ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
        </div>
    </form>
</div>
