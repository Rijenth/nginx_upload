<?php

class shellCommande
{
    /*
        Créer l'utilisateur avec le nom d'utilisateur et le mot de passe spécifiés
        Donner à l'utilisateur la propriété de son espace de stockage
    */
    public function createUser($username, $password) {
        shell_exec(sprintf("sudo useradd -p %s %s -m -s /bin/bash", escapeshellarg($password), escapeshellarg($username)));

        shell_exec(sprintf("sudo chown -R www-data:www-data /home/%s", escapeshellarg($username)));
    }

    /*
        Verifier si le dossier exite ou pas si il exite mesage erreur
        Créer un dossier avec le nom spécifié dans l'espace de stockage de l'utilisateur
        Donner à l'utilisateur la propriété de son dossier

    */
    public function createFolder($username) {
        shell_exec(sprintf("sudo mkdir /home/%s", escapeshellarg($username)));

        shell_exec(sprintf("cd /home/%s && sudo mkdir backups && sudo mkdir sites && sudo mkdir upload && cd ../", escapeshellarg($username)));

        shell_exec(sprintf("sudo chown -R www-data:www-data /home/%s", escapeshellarg($username)));
    }

    /*
        Créer une base de données avec le nom d'utilisateur spécifié
    */
    public function createUserDatabase($username)
    {
        shell_exec(sprintf("sudo sh /var/www/html/serveur-nginx/create_database.sh %s", escapeshellarg($username)));
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
    public function uploadFile($username, $file) {
        if (!isset($file['error']) || is_array($file['error']) || !is_readable($file['tmp_name'])) {
            throw new Exception("Le fichier n'existe pas ou n'est pas lisible.");
        }

        $allowed_extensions = array('jpg', 'txt', 'jpeg', 'png', 'gif', 'doc', 'docx', 'xls', 'xlsx', 'pdf');

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

        if (!in_array(strtolower($extension), $allowed_extensions)) {
            throw new Exception("Le type de fichier n'est pas autorisé.");
        }

        if ($file['size'] > 10000000) {
            throw new Exception("La taille du fichier dépasse la limite autorisée.");
        }

        $destination_dir = "/home/" . $username . "/upload";

        if (!file_exists($destination_dir)) {
            $cmd_mkdir = sprintf("mkdir -p %s", escapeshellarg($destination_dir));
            shell_exec($cmd_mkdir);
        }

        if (!is_writable($destination_dir)) {
            throw new Exception("Le répertoire de destination n'est pas accessible en écriture.");
        }

        $tmp_filename = tempnam(sys_get_temp_dir(), 'upload');

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
    public function listFiles($username): array 
    {
        $output = shell_exec(sprintf("ls /home/%s/upload", escapeshellarg($username)));

        $files = explode("\n", trim($output));

        $files = array_filter($files);

        return $files;
    }

    /*
        Charge les informations de l'utilisateur pour le tableau de bord
    */
    public function getDashboardData($username): array
    {
        $accountSize = shell_exec("du -sh /home/$username | awk '{print $1}'");

        $database_size = shell_exec("sudo du -sh /var/lib/mysql/$username | awk '{print $1}'");

        return [
            "account_size" => $accountSize,
            "database_size" => $database_size
        ];
    }

    /*
        Change le mot de passe de l'utilisateur Linux et MySQL
    */
    public function changePassword($username, $password)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);

        shell_exec(sprintf("sudo echo '%s:%s' | sudo chpasswd", escapeshellarg($username), escapeshellarg($password)));

        $pdo = new PDO('mysql:host=localhost;dbname=db', 'root', '');

        $query = $pdo->prepare("UPDATE user SET password = :password WHERE username = :username");

        $query->execute([
            "password" => $password,
            "username" => $username
        ]);

        shell_exec(sprintf("sudo mysql -u root -e \"UPDATE mysql.user SET Password = PASSWORD('%s') WHERE User = '%s';\"", escapeshellarg($password), escapeshellarg($username)));

        shell_exec("sudo mysql -u root -e \"FLUSH PRIVILEGES;\"");
    }
}
