<?php

require_once __DIR__ . '/../interface.php';

class MySQLiProvider implements DbProvider
{
    public function create_connection(): DbConnection
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        return new MySQLiConnection();
    }
}

class MySQLiConnection implements DbConnection
{
    private ?mysqli $conn = null;

    public function connect(
        ?string $hostname = null,
        ?string $username = null,
        ?string $password = null,
        ?string $database = null,
        ?int $port = null,
        ?string $socket = null
    ) {
        $this->conn?->close();
        $this->conn = new mysqli($hostname, $username, $password, $database, $port, $socket);
    }

    public function close()
    {
        $this->conn?->close();
    }

    public function prepare(string $query): PreparedStatement
    {
        return new MySQLiPreparedStatement($this->conn, $query);
    }

    public int|string $insert_id { get => $this->conn?->insert_id; }
}

class MySQLiPreparedStatement implements PreparedStatement
{
    private mysqli_stmt $stmt;

    public function __construct(mysqli $conn, string $query)
    {
        $this->stmt = $conn->prepare($query);
    }

    public function bind_param(string $types, ...$values)
    {
        $this->stmt->bind_param($types, ...$values);
    }

    public function execute()
    {
        $this->stmt->execute();
    }

    public function get_result(): QueryResult
    {
        $res = $this->stmt->get_result();
        return new MySQLiQueryResult($res);
    }
}

class MySQLiQueryResult implements QueryResult
{
    private mysqli_result $res;

    public function __construct(mysqli_result $res)
    {
        $this->res = $res;
    }

    public function fetch_assoc()
    {
        return $this->res->fetch_assoc();
    }
}
