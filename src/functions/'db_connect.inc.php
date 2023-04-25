<?php 
class DB {
    private $host = 'localhost';
    private $user = 'root';
    private $password = '';
    private $database = 'whatsupp';
    private $db;
    
    public function connectDb()
    {
        try {
            $this->db = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->database,
                $this->user,
                $this->password,
                // on interragit avec la BDD en UTF8 ce qui empêche les problèmes d'accent
                // indique la requête sql à lancer quand on se connecte
                array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
                    //mode d'erreur pour avoir des warning
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
                )
            );
            return $this->db;
            //récupération des erreurs
        } catch (PDOException $e) {
            die('<h1>Impossible de se connecter a la BDD</h1>');
        }
    }
    
}
?>