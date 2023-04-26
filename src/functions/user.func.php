<?php 

require_once(realpath(__DIR__ . DIRECTORY_SEPARATOR . 'dbConnect.func.php'));

// Returns true if any of the input fields are empty
function EmptyInputSignUp($name, $email, $password, $pwdcheck)
{
    return empty($name) || empty($email) || empty($password) || empty($pwdcheck);
}

// Returns true if the username contains any special characters
function InvalidUsername($name)
{
    return !preg_match("/^[a-zA-Z0-9]*$/", $name);
}

// Returns true if the passwords don't match
function pwdMatch($password, $pwdcheck)
{
    return $password === $pwdcheck;
}

// Returns true if any of the login fields are empty
function EmptyInputLogin($username, $pwd)
{
    return empty($username) || empty($pwd);
}

// Returns true if the user exists in the database
function userExists($name, $email)
{
    $dbh = connectToDatabase();

    $request = $dbh->prepare("SELECT * FROM user WHERE name = :name OR email = :email;");

    $request->execute([
        "name" => $name,
        "email" => $email
    ]);

    $result = $request->fetch(PDO::FETCH_ASSOC);
    
    return $result ? true : false;
}

// Creates a new user in the database
function createNewUser($name, $email, $password)
{
    $dbh = connectToDatabase();

    $pass = password_hash($password, PASSWORD_DEFAULT);

    $request = $dbh->prepare("INSERT INTO user (name, email, password) VALUES (:name, :email, :pass);");

    return $request->execute([
        "name" => $name, 
        "email" => $email, 
        "pass" => $pass
    ]);
}

// Logs in the user and creates a session
function login($username, $password)
{
    $dbh = connectToDatabase();

    $request = $dbh->prepare("SELECT * FROM user WHERE name = :name OR email = :email;");
    $request->execute([
        "name" => $username,
        "email" => $username
    ]);
    $result = $request->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        header("location: ../login.php?error=wrongLogin");
        exit();
    }

    $pwdHashed = $result['password'];
    $checkPass = password_verify($password, $pwdHashed);

    if (!$checkPass) {
        header("location: ../login.php?error=wrongLogin");
        exit();
    }

    session_start();
    $_SESSION["userid"] = $result['id'];
    $_SESSION["useruid"] = $result['name'];
    header("location: ../index.php");
    exit();
}

// Connects to the database
function connectToDatabase() {
    try {
        return new PDO('mysql:host=localhost;dbname=db', 'root', '');
    } catch (PDOException $e) {
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
        exit();
    }
}
