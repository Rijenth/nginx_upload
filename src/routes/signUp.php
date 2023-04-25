<?php
require "../functions/user.inc.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = filter_input(INPUT_POST, 'username');
  $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
  $password = filter_input(INPUT_POST, 'password');
  $pwdcheck = filter_input(INPUT_POST, 'passwordCheck');
  require_once "../functions/db_connect.inc.php";

  if (EmptyInputSignUp($name, $email, $password, $pwdcheck) !== false) {
    echo "<script>alert('Empty Input')</script>";
  } elseif (InvalidUsername($name) !== false) {
    echo "<script>alert('Invalid Username')</script>";
  } elseif (pwdMatch($password, $pwdcheck) !== false) {
    echo "<script>alert('Password does not match')</script>";
  } elseif (UsernameExist($name, $email) !== false) {
    echo "<script>alert('Username or Email already exist')</script>";
  } else {
    createUser($name, $email, $password);

    header("location: ../routes/login.php");

    exit();
  }
};

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
    <a href="../index.php" id="goback"> Go back </a>
    <!-- SignUp Form -->
    <form action="#" method="POST">
      <input type="text" name="username" placeholder="Username" />
      <input type="email" name="email" placeholder="Email" />
      <input type="password" name="password" placeholder="Password" />
      <input type="password" name="passwordCheck" placeholder="Confirm Password" />
      <button type="submit" name="submit">SignUp</button>
    </form>
  </body>
</html>
