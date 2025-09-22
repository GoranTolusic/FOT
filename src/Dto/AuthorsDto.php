<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class AuthorsDto
{
    #[Assert\Type('string')]
    #[Assert\Choice(choices: ['ASC', 'DESC'], message: 'Direction must be ASC or DESC.')]
    public string $direction = 'ASC';

    #[Assert\Type('string')]
    #[Assert\Choice(choices: ['id'], message: 'OrderBy must be id, first_name or last_name.')]
    public string $orderBy = 'id';

    #[Assert\Type('string')]
    #[Assert\Regex(pattern: '/^\d+$/', message: 'Page must contain only numbers.')]
    public string $page = '1';

    #[Assert\Type('string')]
    #[Assert\Length(max: 50)]
    public string $query = '';

    public readonly string $limit;

    public function __construct()
    {
        $this->limit = "4";
    }
}
