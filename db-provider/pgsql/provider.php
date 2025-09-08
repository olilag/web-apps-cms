<?php

require_once __DIR__ . '/../interface.php';

use PgSql\Connection;
use PgSql\Result;

class PgSQLProvider implements DbProvider
{
    public function create_connection(): DbConnection
    {
        return new PgSQLConnection();
    }
}

class PgSQLConnection implements DbConnection
{
    private ?Connection $conn = null;
    private int|string|null $last_id = null;

    public function connect(
        ?string $hostname = null,
        ?string $username = null,
        ?string $password = null,
        ?string $database = null,
        ?int $port = null,
        ?string $socket = null
    ) {
        if ($this->conn !== null) {
            pg_close($this->conn);
        }
        $conn_str = $this->construct_connection_string($hostname, $username, $password, $database, $port, $socket);
        $this->conn = pg_connect($conn_str);
    }

    private function construct_connection_string(
        ?string $hostname = null,
        ?string $username = null,
        ?string $password = null,
        ?string $database = null,
        ?int $port = null,
        ?string $socket = null
    ): string {
        $conn_str = "";
        if ($socket !== null) {
            $hostname = $socket;
        }
        if ($hostname !== null) {
            $conn_str .= "host=$hostname ";
        }
        if ($username !== null) {
            $conn_str .= "user=$username ";
        }
        if ($password !== null) {
            $conn_str .= "password=$password ";
        }
        if ($database !== null) {
            $conn_str .= "dbname=$database ";
        }
        if ($port !== null) {
            $conn_str .= "port=$port ";
        }
        $conn_str .= "connect_timeout=5";
        return $conn_str;
    }

    public function close()
    {
        if ($this->conn !== null) {
            pg_close($this->conn);
        }
    }

    public function prepare(string $query): PreparedStatement
    {
        return new PgSQLPreparedStatement($this->conn, $this->last_id, $query);
    }

    public int|string $insert_id {
        get {
            return $this->last_id;
        }
    }
}

class PgSQLPreparedStatement implements PreparedStatement
{
    private Connection $conn;
    private array $params = [];
    private ?Result $result = null;
    private int|string|null $last_id;
    private bool $returning = false;

    public function __construct(Connection $conn, int|string|null &$last_id, string $query)
    {
        $this->conn = $conn;
        $this->last_id = &$last_id;
        $query = $this->replace_query($query);
        pg_prepare($this->conn, "", $query);
    }

    private function replace_query(string $query): string
    {
        $count = substr_count($query, '?');
        for ($i = 1; $i <= $count; $i++) {
            $pos = strpos($query, '?');
            if ($pos !== false) {
                $query = substr_replace($query, "\$$i", $pos, strlen('?'));
            }
        }
        if (str_contains($query, "INSERT")) {
            $query .= "RETURNING id";
            $this->returning = true;
        }
        return $query;
    }

    public function bind_param(string $types, ...$values)
    {
        $this->params = array(...$values);
    }

    public function execute()
    {
        $this->result = pg_execute($this->conn, "", $this->params);
        if ($this->returning) {
            $insert_row = pg_fetch_row($this->result);
            $this->last_id = $insert_row[0];
        }
    }

    public function get_result(): QueryResult
    {
        return new PgSQLQueryResult($this->result);
    }
}

class PgSQLQueryResult implements QueryResult
{
    private Result $res;

    public function __construct(Result $res)
    {
        $this->res = $res;
    }

    public function fetch_assoc()
    {
        return pg_fetch_assoc($this->res);
    }
}
