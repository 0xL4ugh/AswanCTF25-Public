<?php

class DB {
    private static ?DB $instance = null;
    private mysqli $connection;

    private function __construct() {
        $host = $_ENV['dbhost'] ?? 'mysql';
        $user = $_ENV['dbusername'] ?? 'root';
        $pass = $_ENV['dbpassword'] ?? 'root_password';
        $dbname = $_ENV['dbname'] ?? 'bankdb';
        $dbport = $_ENV['dbport'] ?? '3306';

        $this->connection = new mysqli($host, $user, $pass, $dbname,$dbport);

        if ($this->connection->connect_error) {
            die("DB Connection failed: " . $this->connection->connect_error);
        }
    }

    public function __destruct() {
        if ($this->connection !== null) {
            $this->connection->close();
        }
    }
    

    public static function getInstance(): DB {
        if (self::$instance === null) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

    public function getConnection(): mysqli {
        return $this->connection;
    }

}

?>
