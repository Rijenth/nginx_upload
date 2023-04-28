<?php
session_start();
require_once realpath(__DIR__ . DIRECTORY_SEPARATOR . '../../functions/shellCommande.php');

if (!isset($_SESSION["name"]) || !isset($_SESSION["email"])) {
  header("Location: ../logout.php");
  exit();
}

$username = $_SESSION["name"];
$email = $_SESSION["email"];
$user_files = $_SESSION['user_files'] ?? [];
$dashboard_data = $_SESSION['dashboard_data'] ?? [];
$memory_info = $_SESSION['user_memory'] ?? [];
$cpu_info = $_SESSION['user_cpu'] ?? [];
$disk_info = $_SESSION['user_disk'] ?? [];
$shellCommand = new shellCommande();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_FILES['fileToUpload'])) {
    if ($_FILES['fileToUpload']['error'] === UPLOAD_ERR_NO_FILE) {
      echo '<script>alert("Php stopped working")</script>';
    } else {
      $file = $_FILES['fileToUpload'];
      
      try {
        $shellCommand->uploadFile($username, $file);
        
        $_SESSION['user_files'] = $shellCommand->listFiles($username);

        $_SESSION['dashboard_data'] = $shellCommand->getDashboardData($username);

        $user_files = $_SESSION['user_files'];

        $dashboard_data = $_SESSION['dashboard_data'];

        $memory_info = $shellCommand->getMemoryInfo($username);

        $cpu_info = $shellCommand->getCpuInfo($username);

        $disk_info = $shellCommand->getHddInfo($username);

        echo '<script>alert("Upload successful!")</script>';
      } catch (Exception $e) {
        echo '<script>alert("Error: ' . $e->getMessage() . '")</script>';
      }
    }
  }

  if(isset($_POST['newPassword']) && isset($_POST['confirmPassword'])) {
    $newPassword = filter_input(INPUT_POST, 'newPassword');
    $confirmPassword = filter_input(INPUT_POST, 'confirmPassword');

    if (empty($newPassword) || empty($confirmPassword)) {
      echo '<script>alert("Please fill all the fields")</script>';

    } else if ($newPassword !== $confirmPassword) {
        echo '<script>alert("Passwords do not match")</script>';

    } else {
      try {
        $shellCommand->changePassword($username, $newPassword);

        echo '<script>alert("Password changed successfully!")</script>';

      } catch (Exception $e) {
        echo '<script>alert("Error: ' . $e->getMessage() . '")</script>';
      }
    }
  }

  if (isset($_POST['downloadArchive'])) {
    try {
      $shellCommand->createUploadedFileBackup($username);

      echo '<script>alert("Uploaded files backup downloaded successfully!")</script>';

    } catch (Exception $e) {
      echo '<script>alert("Error: ' . $e->getMessage() . '")</script>';
    }
  }
  
  if (isset($_POST['downloadBdd'])) {
    try {
      $shellCommand->createDatabaseBackup($username);

      echo '<script>alert("Database backup downloaded successfully!")</script>';

    } catch (Exception $e) {
      echo '<script>alert("Error: ' . $e->getMessage() . '")</script>';
    }
  }

  if (isset($_POST['createFolder'])) {
    $directoryName = filter_input(INPUT_POST, 'directoryName');
    try {
      $shellCommand->createSpecificFolder($username,$directoryName);

      $_SESSION['user_files'] = $shell->listFiles(strtolower($username));

      $user_files = $_SESSION['user_files'];

      echo '<script>alert("Create Folder successfully!")</script>';

    } catch (Exception $e) {
      echo '<script>alert("Error: ' . $e->getMessage() . '")</script>';
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
      <form id="uploadForm" method="POST" enctype="multipart/form-data" action="#">
        <h1 class="user-welcome">Bonjour <?= $username ?></h1>
        <p class="user-email"><?= $email ?></p>
       <div class="computer-data">
          <p class="user-account-size">Taille de votre repertoire: <?= $dashboard_data['account_size'] ?? '0K' ?></p>
        <p class="user-database-size">Taille de la base de données: <?= $dashboard_data['database_size'] ?? '0K' ?></p>
        <p class="user-total-memory">Total de mémoire: <?= $memory_info['total'] ?? 'OK' ?></p>
        <p class="user-free-memory">Mémoire libre: <?= $memory_info['free'] ?? 'OK' ?></p>
        <p class="user-available-memory">Mémoire Disponible: <?= $memory_info['available'] ?? 'OK' ?></p>
        <p class="user-cpu-usage">Utilisation du CPU: <?= $cpu_info['user'] ?? 'OK' ?></p>
        <p class="user-cpu-system">Système CPU: <?= $cpu_info['system'] ?? 'OK' ?></p>
        <p class="user-cpu-idle">CPU inactif: <?= $cpu_info['idle'] ?? 'OK' ?></p>
        <p class="user-hdd-total">Taille du disque dur: <?= $disk_info['total'] ?? 'OK' ?></p>
        <p class="user-hdd-used">Espace utilisé du disque dur: <?= $disk_info['used'] ?? 'OK' ?></p>
        <p class="user-hdd-available">Espace disponible du disque dur: <?= $disk_info['free'] ?? 'OK' ?></p>
       </div>
        <a href="../logout.php">Deconnexion</a>

        <label for="fileToUpload">
          <button id="uploadFile" name="uploadFile" type="submit">
            <img src="../../assets/img/upload.svg" alt="folder" />
          </button>
        </label>
        <label for="createFolder">
          <button id="createFolder" name="createFolder" type="submit">
            <img src="../../assets/img/createFolder.svg" alt="folder" />
          </button>
        </label>
      </form>
    
   <div class="grouped-actions">
     <!-- Button to download archive of all uploaded files -->
      <form class="downloadUserData" action="#" method="POST">
        <button type="submit" name="downloadArchive" id="downloadArchive">
          <label for="downloadArchive">
            Télécharger mes fichiers utilisateur
          <img src="../../assets/img/download.svg" alt="folder" />
          </label>
        </button>
      </form>
      <!-- Button to download archive of all user's bdd -->
      <form class="downloadUserData" action="#" method="POST">
        <button type="submit" name="downloadBdd" id="downloadBdd">
          <label for="downloadBdd">
            Télécharger mes données utilisateur            
          <img src="../../assets/img/downloadUser.svg" alt="folder" />
          </label>
        </button>
      </form>
      <!-- Button to open modal -->
      <button id="openModal">Réinitialiser le mot de passe</button>
   </div>
    
    <!-- Modal to allow user to reset password -->
      <div id="modal" class="modal">
        <div class="modal-content">
          <span class="close">&times;</span>
          <form id="resetPasswordForm" class="resetPassword" method="POST" action="#">
            <label for="newPassword">Nouveau mot de passe</label>
            <input type="password" name="newPassword" id="newPassword" required>
            <label for="confirmPassword">Confirmer le nouveau mot de passe</label>
            <input type="password" name="confirmPassword" id="confirmPassword" required>
            <button type="submit" name="resetPassword" id="resetPassword">Réinitialiser le mot de passe</button>
          </form>
        </div>
      </div>

      

    </aside>

    <div id="files-n-folder">
      <?php if (empty($user_files)) { ?>
        <p class="empty-folder">Votre dossier est vide</p>
      <?php } ?>

      <?php foreach ($user_files as $filename) { ?>
        <?php $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); ?>
        <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'docx', 'doc', 'txt', 'mp3', 'mp4', 'mkv', 'avi', 'zip', 'rar', '7z', 'tar', 'gz', 'iso', 'exe', 'msi', 'apk', 'deb', 'rpm', 'jar', 'java', 'php', 'html', 'css', 'js', 'py', 'c', 'cpp', 'h', 'hpp', 'sh', 'bat', 'vbs', 'sql', 'xml', 'json', 'yml', 'yaml', 'ini', 'cfg', 'conf', 'log', 'bak', 'bak', 'bak'])) { ?>
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
  <script>
    // Get the form and upload button elements
    const form = document.getElementById('uploadForm');
    const uploadBtn = document.getElementById('uploadFile');

    uploadBtn.addEventListener('click', function(e) {
      e.preventDefault();

      const fileInput = document.createElement('input');
      fileInput.type = 'file';
      fileInput.name = 'fileToUpload';

      fileInput.addEventListener('change', function() {
        const file = fileInput.files[0];
        if (file) {
          form.appendChild(fileInput);
          form.submit();
        } else {
          alert('Javascript stopped working!');
        }
      });

      fileInput.click();
    });

    // Get the modal
    const modal = document.getElementById("modal");
    const modalTrigger = document.getElementById("openModal");

    modalTrigger.addEventListener('click', function() {
      modal.classList.add('active');
    });

    // if user clicks on the close button, close the modal
    const closeBtn = document.getElementsByClassName("close")[0];
    closeBtn.addEventListener('click', function() {
      modal.classList.remove('active');
    });

    // if user clicks anywhere outside of the modal, close it
    window.addEventListener('click', function(event) {
      if (event.target == modal) {
        modal.classList.remove('active');
      }
    });

    // reset password form
    const resetPasswordForm = document.getElementById('resetPasswordForm');
    const newPassword = document.getElementById('newPassword');
    const confirmPassword = document.getElementById('confirmPassword');
    const submitBtn = document.getElementById('resetPassword');

    submitBtn.addEventListener('click', function(e) {
      e.preventDefault();

      if (newPassword.value !== confirmPassword.value) {
        alert('Les mots de passe ne correspondent pas');
      } else {
        resetPasswordForm.submit();
      }
    });

    // create folder form
    const createFolderBtn = document.getElementById('createFolder');
    const createFolderForm = document.createElement('form');
    createFolderForm.method = 'POST';
    createFolderForm.action = '#';

    const createFolderInput = document.createElement('input');
    createFolderInput.type = 'text';
    createFolderInput.name = 'folderName';
    createFolderInput.id = 'folderName';
    createFolderInput.placeholder = 'Nom du dossier';
    createFolderInput.required = true;

    const createFolderSubmit = document.createElement('button');
    createFolderSubmit.type = 'submit';
    createFolderSubmit.name = 'createFolder';
    createFolderSubmit.id = 'createFolder';
    createFolderSubmit.innerText = 'Créer le dossier';
    
    createFolderForm.appendChild(createFolderInput);
    createFolderForm.appendChild(createFolderSubmit);

    createFolderBtn.addEventListener('click', function(e) {
      e.preventDefault();

      createFolderBtn.style.display = 'none';
      createFolderBtn.parentElement.appendChild(createFolderForm);

      createFolderSubmit.addEventListener('click', function(e) {
        e.preventDefault();

        createFolderForm.submit();
      });
    });

    



    

    
  </script>

</body>

</html>
