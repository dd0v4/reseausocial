<?php
require "db.php";
session_start();


session_destroy();

header("Location: login.php");
// Page appelée pour se déconnecter