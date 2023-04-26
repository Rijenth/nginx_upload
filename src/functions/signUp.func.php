<?php
// Check if the form was submitted via POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    // Get user input data
    $name = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $pwdcheck = $_POST['passwordCheck'];

    // Validate user input data
    require_once "dbConnect.func.php";

    require "user.func.php";
    
    if (hasEmptyInputSignUp($name, $email, $password, $pwdcheck)) {
        redirectToSignUpWithError('emptyinput');
    }
    if (hasInvalidUsername($name)) {
        redirectToSignUpWithError('invalidusername');
    }
    if (!doPasswordsMatch($password, $pwdcheck)) {
        redirectToSignUpWithError('passwordsdonotmatch');
    }
    if (doesUsernameOrEmailExist($name, $email)) {
        redirectToSignUpWithError('usernametaken');
    }

    // Create new user
    createNewUser($name, $email, $password);
    header("location: ../routes/login.php");
    exit();

} else {
    // Redirect to sign up page
    header("location: ../routes/signUp.php");
    exit();
}

// Checks if any input field is empty
function hasEmptyInputSignUp($name, $email, $password, $pwdcheck) {
  return empty($name) || empty($email) || empty($password) || empty($pwdcheck);
}

// Checks if the username is invalid
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

// Redirects to the sign up page with an error message in the query string
function redirectToSignUpWithError($error) {
  header("location: ../register.html?error=$error");
  exit();
}
