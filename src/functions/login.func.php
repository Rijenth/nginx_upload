<?php 

// Check if the form was submitted
if (isset($_POST['submit'])) {
    
    // Get the submitted username and password
    $username = $_POST['username'];
    $pwd = $_POST['password'];
    
    // Include the necessary files and functions
    require_once "dbConnect.func.php";
    require_once "user.func.php";
    require_once "fonctions.func.php";
    
    // Check for empty input
    if (EmptyInputLogin($username, $pwd)) {
        header("location: ../Login.php?error=emptyinput");
        exit();
    }
    
    // Authenticate the user
    if (!login($username, $pwd)) {
        header("location: ../Login.php?error=wrongcredentials");
        exit();
    }
    
    // Redirect the user to the home page
    header("location: ../index.php");
    exit();
    
} else {
    // If the form wasn't submitted, redirect to the login page
    header("location: ../login.php");
    exit();
}

?>
