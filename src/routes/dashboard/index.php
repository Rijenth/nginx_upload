<?php
require_once realpath(__DIR__ . DIRECTORY_SEPARATOR . '../../functions/shellCommande.php');
session_start();

if (!isset($_SESSION["name"]) || !isset($_SESSION["email"])) {
  header("Location: ../logout.php");
  exit();
}

$username = $_SESSION["name"];
$email = $_SESSION["email"];
$user_files = $_SESSION['user_files'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!isset($_FILES['fileToUpload']) || $_FILES['fileToUpload']['error'] === UPLOAD_ERR_NO_FILE) {
    echo '<script>alert("Please select a file to upload!")</script>';
  } else {
    $file = $_FILES['fileToUpload'];
    $shellCommande = new shellCommande();

    try {
      $shellCommande->uploadFile($username, $file);
      $_SESSION['user_files'] = $shellCommande->listFiles($username);
      $user_files = $_SESSION['user_files'];
      echo '<script>alert("Upload successful!")</script>';
    } catch (Exception $e) {
      echo '<script>alert("Error: ' . $e->getMessage() . '")</script>';
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard</title>
  <link rel="stylesheet" href="../../assets/styles/dashboard.css"/>
</head>
<body>
<main id="dashboard">
  <aside id="user-data">
    <form id="uploadForm" method="POST" enctype="multipart/form-data">
      <h1 class="user-welcome">Bonjour <?= $username ?></h1>
      <p class="user-email"><?= $email ?></p>
      <a href="../logout.php">Deconnexion</a>

      <label for="fileToUpload">
        <button id="uploadFile" type="button">
          <img src="../../assets/img/upload.svg" alt="folder"/>
        </button>
      </label>
      <input type="file" name="fileToUpload" id="fileToUpload" style="display: none;">
    </form>
  </aside>

  <div id="files-n-folder">
    <?php if (empty($user_files)) { ?>
      <p class="empty-folder">Votre dossier est vide</p>
    <?php } ?>

    <?php foreach ($user_files as $filename) { ?>
      <?php $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); ?>
      <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif','pdf','docx','doc','txt','mp3','mp4','mkv','avi','zip','rar','7z','tar','gz','iso','exe','msi','apk','deb','rpm','jar','java','php','html','css','js','py','c','cpp','h','hpp','sh','bat','vbs','sql','xml','json','yml','yaml','ini','cfg','conf','log','bak','bak','bak'])) { ?>
        <div class="file">
          <img src="../../assets/img/file.svg" alt="file"/>
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
  <script>
  // Get the form and upload button elements
  const form = document.getElementById('uploadForm');
  const uploadBtn = document.getElementById('uploadFile');

  // Add event listener for button click
  uploadBtn.addEventListener('click', function(e) {
    e.preventDefault(); // Prevent default button behavior

    // Create input element for file upload
    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.name = 'fileToUpload';

    // Add change event listener for file input
    fileInput.addEventListener('change', function() {
      // Submit form when file is selected
      form.submit();
    });

    // Click the file input to open the file upload dialog
    fileInput.click();
  });
</script>

</body>

</html>