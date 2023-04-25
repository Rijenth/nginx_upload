<?php 
require_once(realpath(__DIR__ . DIRECTORY_SEPARATOR . 'db_connect.inc.php'));


function EmptyInputSignUp($name, $email, $password, $pwdcheck)
{
    return (empty($name) || empty($email) || empty($password) || empty($pwdcheck));
}

// Vérifie que l'username n'est pas de charactère spéciaux
function InvalidUsername($name)
{
    return (preg_match("/^*[a-zA-Z0-9]$/", $name));
}

// Vérifie que les mdp soit correspondant
function pwdMatch($password, $pwdcheck)
{
    return $password == $pwdcheck;
}

function EmptyInputLogin($username, $pwd)
{
    return empty($username) || empty($pwd);
}

// Vérifie que l'username n'est pas deja utiliser
function UserNameExist($name, $email)
{
    $db = new DB();
    $request = $db->connectDb()->prepare("SELECT * FROM user WHERE username = ? OR email = ?;");
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
    $request = $db->connectDb()->prepare("INSERT INTO user (email, password, username) VALUES (?,?,?);");
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
