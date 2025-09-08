<?php

interface DbProvider
{
    public function create_connection(): DbConnection;
}

interface DbConnection
{
    public function connect(
        ?string $hostname = null,
        ?string $username = null,
        ?string $password = null,
        ?string $database = null,
        ?int $port = null,
        ?string $socket = null
    );
    public function close();
    public function prepare(string $query): PreparedStatement;
    public int|string $insert_id { get; }
}

interface PreparedStatement
{
    public function bind_param(string $types, ...$values);
    public function execute();
    public function get_result(): QueryResult;
}

interface QueryResult
{
    public function fetch_assoc();
}
