<?php 
session_start();
require(realpath(__DIR__ . DIRECTORY_SEPARATOR . 'shellCommande.php'));
$username = null;
$email  = null;
if(isset($_SESSION["name"])) {
    $username = $_SESSION["name"];
}

if(isset($_SESSION["email"])) {
  $email = $_SESSION["email"];
}
if($username === null || $email === null) {
  header("Location: ../logout.php");
  exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_SESSION['name'];
  $file = $_FILES['fileToUpload'];

  try {
    $shellCommande = new shellCommande();
    $shellCommande->uploadFile($username, $file, $destination);
    echo 'Upload successful!';
  } catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
  }
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="../../assets/styles/dashboard.css" />
  </head>
  <body>
    <main id="dashboard">
      <aside id="user-data">
        <h1 class="user-welcome">Bonjour <?= $username ?></h1>
        <p class="user-email"><?= $email ?></p>
        <a href="../logout.php">Deconnexion</a>  
      </aside>
      <div id="files-n-folder">
        <button id="uploadFile">
          <img src="../../assets/img/upload.svg" alt="file" />
        </button>
        <form action="#" method="POST" enctype="multipart/form-data">
          <input type="file" name="fileToUpload" id="fileToUpload">
          <input type="submit" value="Upload" name="submit">
        </form>
        <button id="createFolder">
          <img src="../../assets/img/createFolder.svg" alt="folder" />
        </button>

        <div class="folder">
          <img src="../../assets/img/folder.svg" alt="folder" />
          <p>Folder 1</p>
        </div>
        <div class="file">
          <img src="../../assets/img/file.svg" alt="file" />
          <p>File 1</p>
        </div>
        <div class="folder">
          <img src="../../assets/img/folder.svg" alt="folder" />
          <p>Folder 1</p>
        </div>
        <div class="file">
          <img src="../../assets/img/file.svg" alt="file" />
          <p>File 1</p>
        </div>
        <div class="folder">
          <img src="../../assets/img/folder.svg" alt="folder" />
          <p>Folder 1</p>
        </div>
        <div class="file">
          <img src="../../assets/img/file.svg" alt="file" />
          <p>File 1</p>
        </div>
        <div class="folder">
          <img src="../../assets/img/folder.svg" alt="folder" />
          <p>Folder 1</p>
        </div>
        <div class="file">
          <img src="../../assets/img/file.svg" alt="file" />
          <p>File 1</p>
        </div>
        <div class="folder">
          <img src="../../assets/img/folder.svg" alt="folder" />
          <p>Folder 1</p>
        </div>
        <div class="file">
          <img src="../../assets/img/file.svg" alt="file" />
          <p>File 1</p>
        </div>
        <div class="folder">
          <img src="../../assets/img/folder.svg" alt="folder" />
          <p>Folder 1</p>
        </div>
        <div class="file">
          <img src="../../assets/img/file.svg" alt="file" />
          <p>File 1</p>
        </div>
        <div class="folder">
          <img src="../../assets/img/folder.svg" alt="folder" />
          <p>Folder 1</p>
        </div>
        <div class="file">
          <img src="../../assets/img/file.svg" alt="file" />
          <p>File 1</p>
        </div>
        <div class="folder">
          <img src="../../assets/img/folder.svg" alt="folder" />
          <p>Folder 1</p>
        </div>
        <div class="file">
          <img src="../../assets/img/file.svg" alt="file" />
          <p>File 1</p>
        </div>
        <div class="folder">
          <img src="../../assets/img/folder.svg" alt="folder" />
          <p>Folder 1</p>
        </div>
        <div class="file">
          <img src="../../assets/img/file.svg" alt="file" />
          <p>File 1</p>
        </div>
        <div class="folder">
          <img src="../../assets/img/folder.svg" alt="folder" />
          <p>Folder 1</p>
        </div>
        <div class="file">
          <img src="../../assets/img/file.svg" alt="file" />
          <p>File 1</p>
        </div>
        <div class="folder">
          <img src="../../assets/img/folder.svg" alt="folder" />
          <p>Folder 1</p>
        </div>
        <div class="file">
          <img src="../../assets/img/file.svg" alt="file" />
          <p>File 1</p>
        </div>
      </div>
    </main>
  </body>
</html>
