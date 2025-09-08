<?php

require_once __DIR__ . '/interface.php';

class DbProviders
{
    public static function get_provider(string $provider): DbProvider
    {
        switch ($provider) {
            case "mysqli":
                require_once __DIR__ . '/mysqli/provider.php';
                return new MySQLiProvider();
            case "pgsql":
                require_once __DIR__ . '/pgsql/provider.php';
                return new PgSQLProvider();
            default:
                throw new Exception("Unsupported DB provider");
        }
    }
}
