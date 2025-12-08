<?php

use App\Controller\RecommendationController;

require __DIR__ . '/../vendor/autoload.php';

header('Content-Type: application/json; charset=utf-8');

$controller = new RecommendationController();

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Для маршрутов с параметрами
$contentMatches = [];
preg_match('#^/content/(\d+)$#', $path, $contentMatches);

if ($path === '/profile' && $method === 'POST') {
    $json = file_get_contents("php://input");
    $data = json_decode($json, true) ?: [];
    $controller->saveProfile($data);

} elseif (!empty($contentMatches) && $method === 'GET') {
    $controller->getContent((int)$contentMatches[1]);

} elseif ($path === '/recommendations' && $method === 'GET') {
    $controller->getRecommendations($_GET);

} else {
    http_response_code(404);
    echo json_encode(["error" => "Not found"]);
}

