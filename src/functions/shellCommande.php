<?php

class shellCommande
{
    public function createUser($username, $password) {
        // Créer l'utilisateur avec le nom d'utilisateur et le mot de passe spécifiés
        shell_exec(sprintf("sudo useradd -p %s %s -m -s /bin/bash", escapeshellarg($password), escapeshellarg($username)));

        // Donner à l'utilisateur la propriété de son espace de stockage
        shell_exec(sprintf("sudo chown -R %s:%s /home/%s", escapeshellarg($username), escapeshellarg($username), escapeshellarg($username)));
    }

    public function createFolder($username, $foldername) {
        //verifier si le dossier exite ou pas si il exite mesage erreur

        // Créer un dossier avec le nom spécifié dans l'espace de stockage de l'utilisateur
        shell_exec(sprintf("sudo mkdir /home/%s/%s", escapeshellarg($username), escapeshellarg($foldername)));

        // Donner à l'utilisateur la propriété de son dossier
        shell_exec(sprintf("sudo chown %s:%s /home/%s/%s", escapeshellarg($username), escapeshellarg($username), escapeshellarg($username), escapeshellarg($foldername)));
    }

    public function delete($username, $path) {
        $full_path = "/home/" . $username . "/" . $path;

        if (is_file($full_path)) {
            // Si c'est un fichier, on utilise la commande shell rm pour le supprimer
            $command = sprintf("sudo rm %s", escapeshellarg($full_path));
            shell_exec($command);
        } else if (is_dir($full_path)) {
            // Si c'est un dossier, on utilise la commande shell rm avec l'option -rf pour le supprimer récursivement
            $command = sprintf("sudo rm -rf %s", escapeshellarg($full_path));
            shell_exec($command);
        } else {
            // Si ce n'est ni un fichier ni un dossier, on lance une exception
            throw new Exception("L'élément spécifié n'existe pas ou n'est pas valide.");
        }
    }


    public function uploadFile($username, $file, $destination) {
        // Vérifier si le fichier existe et est lisible
        if (!isset($file['error']) || is_array($file['error']) || !is_readable($file['tmp_name'])) {
            throw new Exception("Le fichier n'existe pas ou n'est pas lisible.");
        }

        // Vérifier le type de fichier
        $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif', 'doc', 'docx', 'xls', 'xlsx', 'pdf');
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (!in_array(strtolower($extension), $allowed_extensions)) {
            throw new Exception("Le type de fichier n'est pas autorisé.");
        }

        // Vérifier la taille du fichier (max 10Mo)
        if ($file['size'] > 10000000) {
            throw new Exception("La taille du fichier dépasse la limite autorisée.");
        }

        // Vérifier si le répertoire de destination existe et est accessible en écriture
        $destination_dir = "/home/" . $username . "/" . $destination;
        if (!file_exists($destination_dir)) {
            $cmd_mkdir = sprintf("mkdir -p %s", escapeshellarg($destination_dir));
            shell_exec($cmd_mkdir);
        }
        if (!is_writable($destination_dir)) {
            throw new Exception("Le répertoire de destination n'est pas accessible en écriture.");
        }

        // Créer un nom de fichier temporaire pour éviter les risques de sécurité
        $tmp_filename = tempnam(sys_get_temp_dir(), 'upload_');

        // Copier le fichier spécifié dans le fichier temporaire
        if (!move_uploaded_file($file['tmp_name'], $tmp_filename)) {
            throw new Exception("Impossible de copier le fichier.");
        }

        // Donner à l'utilisateur la propriété du fichier
        $cmd_chown = sprintf("chown %s:%s %s", escapeshellarg($username), escapeshellarg($username), escapeshellarg($tmp_filename));
        shell_exec($cmd_chown);

        // Déplacer le fichier temporaire dans le répertoire de destination
        $cmd_mv = sprintf("mv %s %s", escapeshellarg($tmp_filename), escapeshellarg($destination_dir . '/' . basename($file['name'])));
        shell_exec($cmd_mv);
    }

    public function listFiles($username, $path) {
        // Afficher le contenu du dossier spécifié dans l'espace de stockage de l'utilisateur
        $output = shell_exec(sprintf("ls /home/%s/%s", escapeshellarg($username), escapeshellarg($path)));
        return explode("\n", trim($output));
    }

}