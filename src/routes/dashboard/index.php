<?php
require(realpath(__DIR__ . DIRECTORY_SEPARATOR . '../../functions/shellCommande.php'));
session_start();

$username = null;

$email  = null;

$user_files = [];

if (isset($_SESSION["name"])) {
  $username = $_SESSION["name"];
}

if (isset($_SESSION["email"])) {
  $email = $_SESSION["email"];
}
if ($username === null || $email === null) {
  header("Location: ../logout.php");
  exit();
}

if (isset($_SESSION['user_files'])) {
  $user_files = $_SESSION['user_files'];
  echo `<script>console.log($user_files)</script>`;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_SESSION['name'];

  $file = $_FILES['fileToUpload'];

  if (!isset($_FILES['fileToUpload']) || $_FILES['fileToUpload']['error'] === UPLOAD_ERR_NO_FILE) {
    echo '<script>alert("Please select a file to upload!")</script>';
  } else {

    $shellCommande = new shellCommande();

    try {
      $shellCommande->uploadFile($username, $file);

      $result = $shellCommande->listFiles($username);

      $_SESSION['user_files'] = $result;

      $user_files = $_SESSION['user_files'];

      echo '<script>alert("Upload successful!")</script>';
    } catch (Exception $e) {
      echo '<script>alert(' . "Error: " . $e->getMessage() . ')</script>';
    }
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

      <form method="POST" enctype="multipart/form-data">
        
        <input type="file" name="fileToUpload" id="fileToUpload">
        <label for="fileToUpload">
          <button id="uploadFile" type="submit" name="submit">
            <img src="../../assets/img/upload.svg" alt="folder" />
          </button>
        </label>
      </form>

    </aside>

    <div id="files-n-folder">
      <?php if (count($user_files) === 0) { ?>
        <p class="empty-folder">Votre dossier est vide</p>
      <?php } ?>

      <?php foreach ($user_files as $filename) { ?>
        <?php if (in_array(end(explode('.', $filename)), ['jpg', 'jpeg', 'png', 'gif','pdf','docx','doc','txt','mp3','mp4','mkv','avi','zip','rar','7z','tar','gz','iso','exe','msi','apk','deb','rpm','jar','java','php','html','css','js','py','c','cpp','h','hpp','sh','bat','vbs','sql','xml','json','yml','yaml','ini','cfg','conf','log','bak','bak','bak'])) { ?>
          <div class="file">
            <img src="../../assets/img/file.svg" alt="file" />
            <p><?= $filename ?></p>
          </div>
        <?php } else { ?>
          <div class="folder">
            <img src="../../assets/img/folder.svg" alt="folder" />
            <p><?= $filename ?></p>
          </div>
        <?php } ?>
      <?php } ?>
    </div>
  </main>
</body>

</html>