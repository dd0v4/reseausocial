<?php
require "db.php";
session_start();

$error = null;

function verify($pdo, $username, $password, $passwordHash){
    $query = "SELECT * FROM Users WHERE username = :username";
    $statement = $pdo->prepare($query);
    $statement->bindParam(':username', $username, PDO::PARAM_STR);
    $statement->execute();
    if ($statement->rowCount() == 0) {
        return "Wrong username or password."; 
    }
    if (!password_verify($password, $passwordHash)){
        return "Wrong username or password."; 
    }
    return true; 
}

try {
    if(isset($_POST["username"], $_POST["password"])){
        $username = $_POST["username"];
        $password = $_POST["password"];
        $query = "SELECT password FROM Users WHERE username = :username";
        $statement = $pdo->prepare($query);
        $statement->bindParam(':username', $username, PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $passwordHash = $result['password'];
        }
        $verificationResult = verify($pdo, $username, $password, $passwordHash);
        if($verificationResult === true){
            $_SESSION["connected"] = true;
            $_SESSION["connectedAs"] = $username;
            header('Location: '. "home.php");
        } else {
            $error = $verificationResult;
        }
    }
} catch(PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>

<head>
    <link rel="stylesheet" href="./assets/css/styles.css">
    <title>Login</title>
</head>
<div class="container">
    <form action="" method="post">
        <h1>Login</h1>
        <div class="formgroup">
            <p>No account ? <a href="register.php">Register</a></p>
            <label for="username">Username</label>
            <input type="text" id="username" name="username">
            <label for="password">Password</label>
            <input type="password" id="password" name="password">
            <button type="submit">Login</button>
            <?php if ($error) : ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
        </div>
    </form>
</div>
