<?php

namespace App\Database;

use PDO;
use App\Config\Config;

class DB
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (!self::$connection) {
            $config = new Config(__DIR__ . '/../../.env');

            $dsn = sprintf(
                "pgsql:host=%s;port=%s;dbname=%s",
                $config->get('db_host'),
                $config->get('db_port'),
                $config->get('db_name')
            );

            self::$connection = new PDO(
                $dsn,
                $config->get('db_user'),
                $config->get('db_password'),
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        }

        return self::$connection;
    }
}
