<?php

$db_config = [
  'server'   => getenv("DB_SERVER"),
  'login'    => getenv("DB_LOGIN"),
  'password' => getenv("DB_PASSWORD"),
  'database' => getenv("DB_NAME"),
  'port' => getenv("DB_PORT"),
  'provider' => getenv("DB_PROVIDER")
];
