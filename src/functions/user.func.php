<?php 

require_once(realpath(__DIR__ . DIRECTORY_SEPARATOR . 'dbConnect.func.php'));

require(realpath(__DIR__ . DIRECTORY_SEPARATOR . 'shellCommande.php'));

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
    $shell = new shellCommande();

    $shell->createUser($name, $password);

    $shell->createFolder($name);

    $dbh = connectToDatabase();

    $pass = password_hash($password, PASSWORD_DEFAULT);

    $request = $dbh->prepare("INSERT INTO user (name, email, password) VALUES (:name, :email, :pass);");

    return $request->execute([
        "name" => strtolower($name), 
        "email" => $email, 
        "pass" => $pass
    ]);
}

// Logs in the user and creates a session
function login($username, $password)
{
    $dbh = connectToDatabase();

    $request = $dbh->prepare("SELECT * FROM user WHERE name = :name;");
    $request->execute([
        "name" => $username
    ]);
    $result = $request->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        // Si l'utilisateur n'existe pas, affiche un message d'erreur
        echo "Nom d'utilisateur ou mot de passe incorrect.";
        return false;
    }

    $pwdHashed = $result['password'];
    $checkPass = password_verify($password, $pwdHashed);

    if($checkPass) {
        session_start();
        $_SESSION["uid"] = $result['id'];
        $_SESSION["name"] = strtolower($result['name']);
        $_SESSION["email"] = $result['email'];

        $shell = new shellCommande();

        $result = $shell->listFiles(strtolower($username));

        $_SESSION['user_files'] = $result;

        header("location: ../routes/dashboard/index.php");
        exit();
    } else {
        // Si le mot de passe est incorrect, affiche un message d'erreur
        echo "Nom d'utilisateur ou mot de passe incorrect.";
        return false;
    }

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
