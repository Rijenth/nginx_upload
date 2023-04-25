<?php 
require_once(realpath(__DIR__ . DIRECTORY_SEPARATOR . 'db_connect.inc.php'));


function EmptyInputSignUp($name, $email, $password, $pwdcheck)
{
    if (empty($name) || empty($email) || empty($password) || empty($pwdcheck)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

// Vérifie que l'username n'est pas de charactère spéciaux
function InvalidUsername($name)
{
    if (preg_match("/^*[a-zA-Z0-9]$/", $name)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

// Vérifie que l'email soit valide
function InvalidEmail($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

// Vérifie que les mdp soit correspondant
function pwdMatch($password, $pwdcheck)
{
    if ($password == $pwdcheck) {
        $result = false;
    } else {
        $result = true;
    }
    return $result;
}
function EmptyInputLogin($username, $pwd)
{
    if (empty($username) || empty($pwd)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}
// Vérifie que l'username n'est pas deja utiliser
function UserNameExist($name, $email)
{
    $db = new DB();
    $request = $db->connectDb()->prepare("SELECT * FROM user WHERE user_username = ? OR user_email = ?;");
    $request->execute([$name, $email]);
    $resultat = $request->fetch(PDO::FETCH_ASSOC);

    if ($resultat) {
        return $resultat;
    } else {
        return false;
    }
}
// Creation of a new Users 
function createUser($name, $email, $password)
{
    $db = new DB();
    $request = $db->connectDb()->prepare("INSERT INTO user (user_email, user_password, user_username) VALUES (?,?,?);");
    $pass = password_hash($password, PASSWORD_DEFAULT);
    $request->execute([$email, $pass, $name]);
}
function loginUser($uid, $pwd)
{
    $uidExist = UserNameExist($uid, $uid);

    if ($uidExist === false) {
        header("location: ../login.php?error=wrongLogin");
        exit();
    }
    $pwdHashed = $uidExist['password'];
    $checkPass = password_verify($pwd, $pwdHashed);
    

    if ($checkPass === false) {
        header("location: ../login.php?error=wrongLogin");
        exit();
    } else if ($checkPass === true) {
        session_start();
        $_SESSION["userid"] = $uidExist['id'];
        $_SESSION["useruid"] = $uidExist['username'];
        header("location: ../index.php");
        exit();
    }
}
