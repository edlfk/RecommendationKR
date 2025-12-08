<?php
namespace App\Repository;

use App\Database\DB;
use App\Model\User;
use PDO;

class UserRepository
{
    private PDO $pdo;
    public function __construct(DB $db)
    {
        $this->pdo = $db->getConnection();
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $row['interests'] = $row['interests'] ? json_decode($row['interests'], true) : [];
        return new User($row);
    }

    public function upsert(array $data): User
    {
        if (isset($data['user_id'])) {
            $id = (int)$data['user_id'];
            $stmt = $this->pdo->prepare('SELECT id FROM users WHERE id = :id');
            $stmt->execute(['id' => $id]);
            if ($stmt->fetch()) {
                $upd = $this->pdo->prepare('UPDATE users SET age=:age, gender=:gender, interests=:interests WHERE id=:id');
                $upd->execute([
                    'age' => $data['age'] ?? null,
                    'gender' => $data['gender'] ?? null,
                    'interests' => json_encode($data['interests'] ?? []),
                    'id' => $id
                ]);
                return $this->findById($id);
            }
        }
        $ins = $this->pdo->prepare('INSERT INTO users (age, gender, interests) VALUES (:age, :gender, :interests) RETURNING *');
        $ins->execute([
            'age' => $data['age'] ?? null,
            'gender' => $data['gender'] ?? null,
            'interests' => json_encode($data['interests'] ?? [])
        ]);
        $row = $ins->fetch(PDO::FETCH_ASSOC);
        $row['interests'] = $row['interests'] ? json_decode($row['interests'], true) : [];
        return new User($row);
    }
}
