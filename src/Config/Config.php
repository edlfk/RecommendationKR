<?php
namespace App\Config;

use Dotenv\Dotenv;

class Config
{
    private array $data;

    public function __construct(string $envPath = null)
    {
        if ($envPath && file_exists(dirname($envPath))) {
            $dotenv = Dotenv::createImmutable(dirname($envPath));
            $dotenv->load();
        }
        $this->data = [
            'db_host' => getenv('DB_HOST') ?: 'db',
            'db_port' => getenv('DB_PORT') ?: '5432',
            'db_name' => getenv('DB_DATABASE') ?: 'recommendation_db',
            'db_user' => getenv('DB_USER') ?: 'recuser',
            'db_password' => getenv('DB_PASSWORD') ?: 'recpass',
        ];
    }

    public function get(string $key)
    {
        return $this->data[$key] ?? null;
    }
}
