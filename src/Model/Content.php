<?php
namespace App\Model;

class Content
{
    public int $id;
    public string $title;
    public string $description;
    public array $tags = [];
    public int $popularity = 0;

    public function __construct(array $data)
    {
        $this->id = (int)($data['id'] ?? 0);
        $this->title = $data['title'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->tags = isset($data['tags']) ? $data['tags'] : [];
        $this->popularity = (int)($data['popularity'] ?? 0);
    }
}
