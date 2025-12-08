<?php

use PHPUnit\Framework\TestCase;
use App\Controller\RecommendationController;

class RecommendationControllerTest extends TestCase
{
    public function testEmptyUserIdReturnsError()
    {
        ob_start();
        (new RecommendationController())->getRecommendations([]);
        $output = ob_get_clean();
        $result = json_decode($output, true);

        $this->assertArrayHasKey('error', $result);
        $this->assertEquals('Не указан user_id', $result['error']);
    }

    public function testEmptyInterests()
    {
        $controller = $this->createMock(RecommendationController::class);

        $reflection = new ReflectionClass(RecommendationController::class);
        $method = $reflection->getMethod('getRecommendations');
        $method->setAccessible(true);

        ob_start();
        $method->invokeArgs(new RecommendationController(), [['user_id' => 999]]);
        $result = json_decode(ob_get_clean(), true);

        $this->assertTrue(isset($result['error']) || empty($result));
    }
}
