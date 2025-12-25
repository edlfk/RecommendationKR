<?php
namespace App;

use App\Config\Config;
use App\Controller\RecommendationController;
use App\Database\DB;
use Psr\Container\ContainerInterface;
use DI\ContainerBuilder;

class App
{
    private $container;

    public function __construct()
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions([
            Config::class => function () {
                return new Config(__DIR__ . '/../.env');
            },
            DB::class => function ($c) {
                /** @var Config $cfg */
                $cfg = $c->get(Config::class);
                return new DB($cfg);
            },

            RecommendationController::class => function ($c) {
                return new RecommendationController($c->get(RecommendationService::class));
            }
        ]);
        $this->container = $builder->build();
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
