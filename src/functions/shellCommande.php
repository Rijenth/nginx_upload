<?php

class shellCommande
{
    /*
        Créer l'utilisateur avec le nom d'utilisateur et le mot de passe spécifiés
        Donner à l'utilisateur la propriété de son espace de stockage
    */
    public function createUser($username, $password) {
        shell_exec(sprintf("sudo useradd -p %s %s -m -s /bin/bash", escapeshellarg($password), escapeshellarg($username)));

        shell_exec(sprintf("sudo chown -R %s:%s /home/%s", escapeshellarg($username), escapeshellarg($username), escapeshellarg($username)));
    }

    /*
        Verifier si le dossier exite ou pas si il exite mesage erreur
        Créer un dossier avec le nom spécifié dans l'espace de stockage de l'utilisateur
        Donner à l'utilisateur la propriété de son dossier

    */
    public function createFolder($username) {
        shell_exec(sprintf("sudo mkdir /home/%s", escapeshellarg($username)));

        shell_exec(sprintf("cd /home/%s && sudo mkdir backups && sudo mkdir sites && cd ../", escapeshellarg($username)));

        shell_exec(sprintf("sudo chown -R %s:%s /home/%s", escapeshellarg($username), escapeshellarg($username), escapeshellarg($username)));
    }

    /*
        Supprimer l'utilisateur avec le nom d'utilisateur spécifié
        Supprimer l'espace de stockage de l'utilisateur
    */
    public function delete($username, $path) {
        $full_path = "/home/" . $username . "/" . $path;

        if (is_file($full_path)) {
            $command = sprintf("sudo rm %s", escapeshellarg($full_path));
            shell_exec($command);
        } else if (is_dir($full_path)) {
            $command = sprintf("sudo rm -rf %s", escapeshellarg($full_path));
            shell_exec($command);
        } else {
            throw new Exception("L'élément spécifié n'existe pas ou n'est pas valide.");
        }
    }


    /*
        Télécharger un fichier dans l'espace de stockage de l'utilisateur
    */
    public function uploadFile($username, $file, $destination) {
        if (!isset($file['error']) || is_array($file['error']) || !is_readable($file['tmp_name'])) {
            throw new Exception("Le fichier n'existe pas ou n'est pas lisible.");
        }

        $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif', 'doc', 'docx', 'xls', 'xlsx', 'pdf');
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (!in_array(strtolower($extension), $allowed_extensions)) {
            throw new Exception("Le type de fichier n'est pas autorisé.");
        }

        if ($file['size'] > 10000000) {
            throw new Exception("La taille du fichier dépasse la limite autorisée.");
        }

        $destination_dir = "/home/" . $username . "/" . $destination;
        if (!file_exists($destination_dir)) {
            $cmd_mkdir = sprintf("mkdir -p %s", escapeshellarg($destination_dir));
            shell_exec($cmd_mkdir);
        }
        if (!is_writable($destination_dir)) {
            throw new Exception("Le répertoire de destination n'est pas accessible en écriture.");
        }

        $tmp_filename = tempnam(sys_get_temp_dir(), 'upload_');

        if (!move_uploaded_file($file['tmp_name'], $tmp_filename)) {
            throw new Exception("Impossible de copier le fichier.");
        }

        $cmd_chown = sprintf("chown %s:%s %s", escapeshellarg($username), escapeshellarg($username), escapeshellarg($tmp_filename));
        shell_exec($cmd_chown);

        $cmd_mv = sprintf("mv %s %s", escapeshellarg($tmp_filename), escapeshellarg($destination_dir . '/' . basename($file['name'])));
        shell_exec($cmd_mv);
    }

    /*
        Afficher le contenu du dossier spécifié dans l'espace de stockage de l'utilisateur
    */
    public function listFiles($username, $path) {
        $output = shell_exec(sprintf("ls /home/%s/%s", escapeshellarg($username), escapeshellarg($path)));
        return explode("\n", trim($output));
    }

}