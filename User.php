<?php

require "db.php";

class User {
    private $username;
    private $password;
    private $passwordConfirm;
    private $pdo;
    //On crée une interface user avec 4 propriétés : pseudo, mot de passe, mot de passe 2, et le pdo

    public function __construct($username, $password, $passwordConfirm, $pdo) {
        $this->username = $username;
        $this->password = $password;
        $this->passwordConfirm = $passwordConfirm;
        $this->pdo = $pdo;
    }

    // On crée un constructeur avec nos propriétés

    public function validateCredentials() {
        if ($this->password != $this->passwordConfirm) {
            return "Passwords don't match.";
        }
        if (!preg_match('/^[a-zA-Z0-9_]{4,20}$/', $this->username)) {
            return "Username must be 4-20 characters long and can contain letters, numbers, and underscores only.";
        }
        if (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z]{8,30}$/', $this->password)) {
            return "Password must be 8-30 characters long and include at least one letter and one number.";
        }
        $query = "SELECT * FROM Users WHERE username = :username";
        $statement = $this->pdo->prepare($query);
        $statement->bindParam(':username', $this->username, PDO::PARAM_STR);
        $statement->execute();
        if ($statement->rowCount() > 0) {
            return "This username is already used.";
        }
        return true;
    }
    // Fonction qui permet de vérifier si le pseudo est déjà présent dans la base de donnée, et vérifie si les deux mots de passes sont identiques
    public function registerUser() {
        $verifyResult = $this->validateCredentials();
        if ($verifyResult === true) {
            $passwordHash = password_hash($this->password, PASSWORD_DEFAULT, ["cost" => 12]);

            $query = "INSERT INTO Users (username, password) VALUES (?, ?)";
            $statement = $this->pdo->prepare($query);
            if ($statement->execute([$this->username, $passwordHash])) {
                return htmlentities($this->username) . " successfully registered.";
            } else {
                return "Error inserting data into database.";
            }
        } else {
            return $verifyResult;
        }
    }
    // Fonction pour register notre user dans la base de donnée, et hash son mot de passe avec 12 de cost
}

?>
