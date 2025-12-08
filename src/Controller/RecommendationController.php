<?php
namespace App\Controller;

use App\Config\Config;
use PDO;

class RecommendationController
{
    private PDO $pdo;

    public function __construct()
    {
        $config = new Config(__DIR__ . '/../../.env');
        $this->pdo = new PDO(
            "pgsql:host={$config->get('db_host')};port={$config->get('db_port')};dbname={$config->get('db_name')}",
            $config->get('db_user'),
            $config->get('db_password')
        );
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

   // Сохраняем/обновляем профиль пользователя в БД
    public function saveProfile(array $data = null): void
    {
        if (!$data || !isset($data['user_id']) || !isset($data['interests'])) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid JSON or missing required fields"]);
            return;
        }

        // Преобразуем интересы в JSON
        $interestsJson = json_encode($data['interests'], JSON_UNESCAPED_UNICODE);

        // Вставка или обновление записи в таблице users
        $stmt = $this->pdo->prepare("
            INSERT INTO users (id, interests, age, gender)
            VALUES (:id, :interests, :age, :gender)
            ON CONFLICT (id) DO UPDATE 
            SET interests = EXCLUDED.interests,
                age = EXCLUDED.age,
                gender = EXCLUDED.gender"   );

        $stmt->execute([
            'id' => $data['user_id'],
            'interests' => $interestsJson,
            'age' => $data['age'] ?? null,
            'gender' => $data['gender'] ?? null
        ]);

        echo json_encode(["status" => "ok"]);
    }


    // Генерация персонализированных рекомендаций
    public function getRecommendations(array $params)
    {
        $userId = $params['user_id'] ?? null;
        if (!$userId) {
            echo json_encode(['error' => 'Не указан user_id'], JSON_UNESCAPED_UNICODE);
            return;
        }

        // Получаем интересы пользователя
        $stmt = $this->pdo->prepare("SELECT interests FROM users WHERE id=:id");
        $stmt->execute(['id' => $userId]);
        $interestsJson = $stmt->fetchColumn();

        if (!$interestsJson) {
            echo json_encode(['error' => 'Профиль пользователя не найден'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $interests = json_decode($interestsJson, true);

        if (empty($interests)) {
            echo json_encode(['error' => 'У пользователя нет интересов'], JSON_UNESCAPED_UNICODE);
            return;
        }

        // Получаем контент по интересам
        $placeholders = implode(',', array_fill(0, count($interests), '?'));
        $sql = "
            SELECT *
            FROM content
            WHERE EXISTS (
                SELECT 1 FROM jsonb_array_elements_text(tags) t(tag)
                WHERE t.tag IN ($placeholders)
            )
            ORDER BY popularity DESC
            LIMIT 10
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($interests);
        $recommendations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($recommendations, JSON_UNESCAPED_UNICODE);
    }

    // Получение конкретного контента
    public function getContent(int $id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM content WHERE id=:id");
        $stmt->execute(['id' => $id]);
        $content = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$content) {
            http_response_code(404);
            echo json_encode(['error' => 'Контент не найден'], JSON_UNESCAPED_UNICODE);
            return;
        }

        echo json_encode($content, JSON_UNESCAPED_UNICODE);
    }
}
