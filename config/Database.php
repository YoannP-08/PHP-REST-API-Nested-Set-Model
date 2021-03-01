<?php 
    
    class Database {
        // Defining DB Params
        private $host = '127.0.0.1';
        private $db_name = 'docebo';
        private $username = ''; // Change SQL user CREDENTIAL with <your-user>
        private $password = ''; // Change SQL password CREDENTIAL with <your-password>
        private $conn;

        // Connecting DB
        public function connect_db() {
            $this->conn = null;

            try {
                $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name;
                $this->conn = new PDO($dsn, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $this->conn;
            } catch(PDOException $err) {
                echo 'Connection Error: ' . $err->getMessage() . PHP_EOL;
            };
        }
    };
?>