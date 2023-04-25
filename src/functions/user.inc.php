<?php 
require_once(realpath(__DIR__ . DIRECTORY_SEPARATOR . 'db_connect.inc.php'));


function EmptyInputSignUp($name, $email, $password, $pwdcheck)
{
    return (empty($name) || empty($email) || empty($password) || empty($pwdcheck));
}

// Vérifie que l'username n'est pas de charactère spéciaux
function InvalidUsername($name)
{
    if (!preg_match("/^[a-zA-Z0-9]*$/", $name)) {
        return true;
    } else {
        return false;
    }
}

// Vérifie que les mdp soit correspondant
function pwdMatch($password, $pwdcheck)
{
    return $password === $pwdcheck;
}

function EmptyInputLogin($username, $pwd)
{
    return empty($username) || empty($pwd);
}

// Vérifie que l'username n'est pas deja utiliser
function UserNameExist($name, $email)
{
   $db = new DB();

    try {
    $dbh = new PDO('mysql:host=localhost;dbname=db', 'root', '');
    } catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
    exit();
    }

  

    $request = $dbh->prepare("SELECT * FROM user WHERE name = :name OR email = :mail;");

    $request->execute([
	"name" => $name,
	"mail" => $email
    ]);

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
    
    try {
    $dbh = new PDO('mysql:host=localhost;dbname=db', 'root', '');
    } catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
    exit();
    }

    $request = $dbh->prepare("INSERT INTO user (name, email, password) VALUES (:name, :email, :pass);");
    $pass = password_hash($password, PASSWORD_DEFAULT);
    
	if($request->execute([
	"name" => $name, 
	"email" => $email, 
	"pass" => $pass
    ])) {
	return null;
    } else {
    	return false;
    }
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
