<?php

use PHPUnit\Framework\TestCase;

class ApiEndpointsTest extends TestCase
{
    private string $baseUrl = "http://web";

    // Получение конкретного контента
    public function testGetContent()
    {
        $url = $this->baseUrl . "/content/1";

        $json = file_get_contents($url);
        $this->assertNotFalse($json, "Ответ пустой или сервер не доступен: $url");

        $data = json_decode($json, true);
        $this->assertNotNull($data, "Ответ не JSON: $json");

        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('title', $data);
        $this->assertEquals(1, $data['id']);
    }

    // Сохранение/обновление профиля пользователя
    public function testSaveProfile()
    {
        $payload = [
            "user_id" => 11,
            "age" => 29,
            "gender" => "женский",
            "interests" => ["IT", "кино"]
        ];

        $opts = [
            "http" => [
                "method" => "POST",
                "header" => "Content-Type: application/json\r\n",
                "content" => json_encode($payload, JSON_UNESCAPED_UNICODE),
            ]
        ];

        $context = stream_context_create($opts);
        $url = $this->baseUrl . "/profile";

        $responseRaw = file_get_contents($url, false, $context);
        $this->assertNotFalse($responseRaw, "Не получен ответ от сервера: $url");

        $response = json_decode($responseRaw, true);
        $this->assertNotNull($response, "Ответ не JSON: $responseRaw");

        $this->assertArrayHasKey("status", $response);
        $this->assertEquals("ok", $response["status"]);
    }

    // Получение рекомендаций пользователя
    public function testGetRecommendations()
    {
        $url = $this->baseUrl . "/recommendations?user_id=1";

        $json = file_get_contents($url);
        $this->assertNotFalse($json, "Не получены данные рекомендаций: $url");

        $data = json_decode($json, true);
        $this->assertNotNull($data, "Ответ не JSON: $json");
        $this->assertIsArray($data, "Ожидается массив рекомендаций");
    }
}
