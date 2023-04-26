<?php 

// Define a class to handle database connection
class DB {
    // Set the private properties for the database credentials
    private $host = 'localhost';
    private $user = 'root';
    private $password = '';
    private $database = 'db';
    private $db;
    
    // Define a public method to connect to the database
    public function connectDb()
    {
        try {
            // Connect to the database using PDO
            $this->db = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->database,
                $this->user,
                $this->password,
                // Set the default character set to UTF-8 to handle special characters
                // Set the error mode to warning to display errors
                array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
                )
            );
            
            // Return the database object
            return $this->db;
        } 
        // Catch any exceptions that occur and output an error message
        catch (PDOException $e) {
            die('<h1>Unable to connect to the database</h1>');
        }
    } 
} 
?>
