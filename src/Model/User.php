<?php
namespace App\Model;

class User
{
    public int $id;
    public ?int $age;
    public ?string $gender;
    public array $interests = [];

    public function __construct(array $data)
    {
        $this->id = (int)($data['id'] ?? 0);
        $this->age = isset($data['age']) ? (int)$data['age'] : null;
        $this->gender = $data['gender'] ?? null;
        $this->interests = isset($data['interests']) ? $data['interests'] : [];
    }
}
