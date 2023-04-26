<?php
require "../functions/user.func.php";
require_once "../functions/dbConnect.func.php";

// Check if the form was submitted via POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Get user input data and filter/sanitize it
  $name = filter_input(INPUT_POST, 'username');
  $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
  $password = filter_input(INPUT_POST, 'password');
  $pwdcheck = filter_input(INPUT_POST, 'passwordCheck');

  // Check for any input errors
  if (hasEmptyInput($name, $email, $password, $pwdcheck)) {
    displayError('Empty Input');
  } elseif (hasInvalidUsername($name)) {
    displayError('Invalid Username');
  } elseif (!doPasswordsMatch($password, $pwdcheck)) {
    displayError('Passwords do not match');
  } elseif (doesUsernameOrEmailExist($name, $email)) {
    displayError('Username or Email already exists');
  } else {
    // Try to create a new user in the database

    $result = createNewUser($name, $email, $password);

    if ($result === null) {
      // Error occurred during database operation
      displayError('An error occurred during a database query');
    } else {
      // User created successfully, redirect to login page
      header("location: ../routes/login.php");
      exit();
    }
  }
}

// Checks if any input field is empty
function hasEmptyInput($name, $email, $password, $pwdcheck) {
  return empty($name) || empty($email) || empty($password) || empty($pwdcheck);
}

// Checks if the username is valid
function hasInvalidUsername($name) {
  // TODO: implement your own logic here
  return false;
}

// Checks if the two passwords match
function doPasswordsMatch($password, $pwdcheck) {
  return $password === $pwdcheck;
}

// Checks if the given username or email already exists in the database
function doesUsernameOrEmailExist($name, $email) {
  // TODO: implement your own logic here
  return false;
}

// Displays an error message as a JavaScript alert
function displayError($message) {
  echo "<script>alert('$message')</script>";
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
    <a href="../index.php" id="goback"> Go back </a>
    <!-- SignUp Form -->
    <form action="#" method="POST">
      <input type="text" name="username" placeholder="Username" value="test" />
      <input type="email" name="email" placeholder="Email" value="test@mail.fr" />
      <input type="password" name="password" placeholder="Password" value="test" />
      <input type="password" name="passwordCheck" placeholder="Confirm Password" value="test" />
      <button type="submit" name="submit">SignUp</button>
    </form>
  </body>
</html>
