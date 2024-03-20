<?php

require "db.php";

$error = null;

function verify($password, $confirmPassword, $username, $pdo) {
    if ($password != $confirmPassword) {
        return "Passwords don't match.";
    }
    if (!preg_match('/^[a-zA-Z0-9_]{4,20}$/', $username)) {
        return "Username must be 4-20 characters long and can contain letters, numbers, and underscores only.";
    }
    if (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z]{8,30}$/', $password)) {
        return "Password must be 8-30 characters long and include at least one letter and one number.";
    }
    $query = "SELECT * FROM Users WHERE username = :username";
    $statement = $pdo->prepare($query);
    $statement->bindParam(':username', $username, PDO::PARAM_STR);
    $statement->execute();
    if ($statement->rowCount() > 0) {
        return "This username is already used.";
    }
    return true;
}

try {
    if (isset($_POST["username"], $_POST["password"], $_POST["passwordConfirm"])) {
        $verifyResult = verify($_POST["password"], $_POST["passwordConfirm"], $_POST["username"], $pdo);
        if ($verifyResult === true) {
            $username = $_POST["username"];
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT, ["cost" => 12]);

            $query = "INSERT INTO Users (username, password) VALUES (?, ?)";
            $statement = $pdo->prepare($query);
            if ($statement->execute([$username, $password])) {
                echo htmlentities($username) . " successfully registered.";
                exit;
            } else {
                $error = "Error inserting data into database.";
            }
        } else {
            $error = $verifyResult;
        }
    }
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}

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
