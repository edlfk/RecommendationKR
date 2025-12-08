<?php
namespace App\Repository;

use App\Database\DB;
use App\Model\Content;
use PDO;

class ContentRepository
{
    private PDO $pdo;
    public function __construct(DB $db)
    {
        $this->pdo = $db->getConnection();
    }

    /**
     * @return Content[]
     */
    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM content');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];
        foreach ($rows as $row) {
            $row['tags'] = $row['tags'] ? json_decode($row['tags'], true) : [];
            $result[] = new Content($row);
        }
        return $result;
    }

    public function findById(int $id): ?Content
    {
        $stmt = $this->pdo->prepare('SELECT * FROM content WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;
        $row['tags'] = $row['tags'] ? json_decode($row['tags'], true) : [];
        return new Content($row);
    }
}
