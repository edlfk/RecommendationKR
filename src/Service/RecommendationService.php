<?php
namespace App\Service;

use App\Repository\UserRepository;
use App\Repository\ContentRepository;
use App\Model\User;
use App\Model\Content;

class RecommendationService
{
    private UserRepository $userRepo;
    private ContentRepository $contentRepo;

    private float $alpha = 0.7;
    private float $beta = 0.3;

    public function __construct(UserRepository $uRepo, ContentRepository $cRepo)
    {
        $this->userRepo = $uRepo;
        $this->contentRepo = $cRepo;
    }

    /**
     * @return Content[] sorted by score desc
     */
    public function getRecommendations(int $userId, int $limit = 10): array
    {
        $user = $this->userRepo->findById($userId);
        if (!$user) return [];

        $allContent = $this->contentRepo->findAll();
        if (empty($allContent)) return [];

        $maxPopularity = max(array_map(fn($c) => $c->popularity, $allContent)) ?: 1;

        $scored = [];
        foreach ($allContent as $content) {
            $tagScore = $this->tagOverlapScore($user->interests, $content->tags);
            $popScore = $content->popularity / $maxPopularity;
            $score = $this->alpha * $tagScore + $this->beta * $popScore;
            $scored[] = ['content' => $content, 'score' => $score];
        }

        usort($scored, fn($a, $b) => $b['score'] <=> $a['score']);

        $top = array_slice($scored, 0, $limit);
        return array_map(fn($x) => $x['content'], $top);
    }

    private function tagOverlapScore(array $userTags, array $contentTags): float
    {
        if (empty($userTags) || empty($contentTags)) return 0.0;
        $intersection = array_intersect(
            array_map('mb_strtolower', $userTags),
            array_map('mb_strtolower', $contentTags)
        );
        $union = array_unique(array_merge(array_map('mb_strtolower', $userTags), array_map('mb_strtolower', $contentTags)));
        if (count($union) === 0) return 0.0;
        return count($intersection) / count($union);
    }
}
