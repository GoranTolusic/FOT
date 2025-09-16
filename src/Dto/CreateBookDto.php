<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CreateBookDto
{
    #[Assert\NotBlank(message: "Author ID is required.")]
    #[Assert\Type(type: "digit", message: "Author ID must be a numeric string.")]
    public ?string $author = null;

    #[Assert\NotBlank(message: "Title is required.")]
    #[Assert\Length(max: 100, maxMessage: "Title cannot be longer than {{ limit }} characters.")]
    public ?string $title = null;

    #[Assert\NotBlank(message: "Release date is required.")]
    #[Assert\Date(message: "Release date must be a valid date.")]
    public ?string $release_date = null;

    #[Assert\NotBlank(message: "Description is required.")]
    #[Assert\Length(max: 250, maxMessage: "Description cannot be longer than {{ limit }} characters.")]
    public ?string $description = null;

    #[Assert\NotBlank(message: "ISBN is required.")]
    #[Assert\Length(max: 20, maxMessage: "ISBN cannot be longer than {{ limit }} characters.")]
    public ?string $isbn = null;

    #[Assert\NotBlank(message: "Format is required.")]
    #[Assert\Length(max: 50, maxMessage: "Format cannot be longer than {{ limit }} characters.")]
    public ?string $format = null;

    #[Assert\NotBlank(message: "Number of pages is required.")]
    #[Assert\Type(type: "digit", message: "Number of pages must be a numeric string.")]
    public ?string $number_of_pages = null;

    public function formatToArray(): array
    {
        return [
            'author' => [
                'id' => (int) $this->author, 
            ],
            'title' => $this->title,
            'release_date' => (new \DateTime($this->release_date))->format('Y-m-d\TH:i:s.v\Z'),
            'description' => $this->description,
            'isbn' => $this->isbn,
            'format' => $this->format,
            'number_of_pages' => (int) $this->number_of_pages,
        ];
    }
}