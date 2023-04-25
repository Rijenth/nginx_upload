<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="../assets/styles/form.css" />
  </head>
  <body>
    <a href="../index.php" id="goback"> Go back </a>
    <!-- Login Form -->
    <form action="../functions/login.php" method="post">
      <input type="text" name="username" placeholder="Username" />
      <input type="password" name="password" placeholder="Password" />
      <button type="submit" name="submit">Login</button>
    </form>
  </body>
</html>
