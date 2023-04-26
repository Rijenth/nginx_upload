<?php 
require(realpath(__DIR__ . DIRECTORY_SEPARATOR . '../../functions/shellCommande.php'));
session_start();

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

  $shellCommande = new shellCommande();

  try {
    $shellCommande->uploadFile($username, $file);
    
    echo '<script>alert("Upload successful!")</script>';
    
  } catch (Exception $e) {
    echo '<script>alert(' . "Error: " . $e->getMessage() .')</script>';
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

        <hr>
        
        <h2>Upload un fichier</h2>
        <p>Vous pouvez upload un fichier en cliquant sur le bouton ci-dessous</p>
        
        <hr>

        <form action="#" method="POST" enctype="multipart/form-data">
          <input type="file" name="fileToUpload" id="fileToUpload">
          <button id="createFolder" type="submit" name="submit">
            <img src="../../assets/img/createFolder.svg" alt="folder" />
          </button>
        </form>

      </aside>
      <div id="files-n-folder">
        <!-- <button id="uploadFile">
          <img src="../../assets/img/upload.svg" alt="file" />
        </button> -->

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
