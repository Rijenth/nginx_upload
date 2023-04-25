<?php 
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $pwd = $_POST['password'];

    require_once "db_connect.inc.php";
    require_once "user.inc.php";
    // Fonctions dans fonctions.inc.php
    if (EmptyInputLogin($username, $pwd) !== false) {
        header("location: ../Login.php?error=emptyinput");
        exit();
    }
    loginUser($username, $pwd);
} else {
    header("location: ../login.php");
    exit();
}

?>