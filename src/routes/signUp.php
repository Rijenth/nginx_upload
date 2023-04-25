<?php

$name = filter_input(INPUT_POST, 'username');
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = filter_input(INPUT_POST, 'password');
$pwdcheck = filter_input(INPUT_POST, 'passwordCheck');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

  header("location: ../routes/login.html");
} else {
  header("location: ../routes/signUp.php");
  exit();
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SignUp</title>
    <link rel="stylesheet" href="../assets/styles/form.css" />
  </head>
  <body>
    <a href="../index.html" id="goback"> Go back </a>
    <!-- SignUp Form -->
    <form method="post">
      <input type="text" name="username" placeholder="Username" />
      <input type="email" name="email" placeholder="Email" />
      <input type="password" name="password" placeholder="Password" />
      <input type="password" name="passwordCheck" placeholder="Confirm Password" />
      <button type="submit" name="submit">SignUp</button>
    </form>
  </body>
</html>
