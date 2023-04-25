<?php
// Check que l'utilisateur est accès à cet page après avoir tenter de se créer un compte
if (isset($_POST['submit'])) {
    var_dump($_POST);
    die;

    $name = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $pwdcheck = $_POST['passwordCheck'];

    // Management des erreurs

    require_once "db_connect.inc.php";
    require "user.inc.php";
    if (EmptyInputSignUp($name, $email, $password, $pwdcheck) !== false) {
        header("location: ../register.html?error=emptyinput");
        exit();
    }
    if (InvalidUsername($name) !== false) {
        header("location: ../register.html?error=InvalidUsername");
        exit();
    }
    if (pwdMatch($password, $pwdcheck) !== false) {
        header("location: ../register.html?error=passwordAreDifferent");
        exit();
    }
    if (UsernameExist($name, $email) !== false) {
        header("location: ../register.html?error=usernameTaken");
        exit();
    }

    createUser($name, $email, $password);
    header("location: ../routes/login.php");
} else {
    header("location: ../routes/signUp.php");
    exit();
}