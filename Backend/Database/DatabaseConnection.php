<?php

namespace CCS\Database;

use PDO;
use Exception;

class DatabaseConnection
{
    private $connection = null;

    public function __construct()
    {
        $host = DB_HOST;
        $port = DB_PORT;
        $username = DB_USERNAME;
        $password = DB_PASSWORD;
        $dbname = DB_NAME;
        $dialect = DB_DIALECT;
        $charset = DB_CHARSET;

        $this->connection = new PDO(
            "${dialect}:host=${host};port=${port};dbname=${dbname};charset=${charset}",
            $username,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );

        if (mysqli_connect_errno()) {
            throw new Exception("Could not connect to database.");
        }
    }

    public function __destruct()
    {
        $this->connection = null;
    }

    public function query(string $query, array $params = [], array $options = [])
    {
        $stmt = $this->connection->prepare($query, $options);

        if ($stmt === false) {
            throw new Exception("Unable to do prepared statement: " . $query);
        }

        if ($stmt->execute($params) === false) {
            throw new Exception("Statement execution failed: " . $query);
        }

        return $stmt;
    }
}
