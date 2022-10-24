<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez renseigner un titre')]
    #[Assert\Length(min: 100, max: 255, minMessage: 'Le titre doit faire au moins {{ limit }} caractères', maxMessage: 'Le titre doit faire au plus {{ limit }} caractères')]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Veuillez détailler votre question')]
    #[Assert\Length(min: 100, minMessage: 'Veuillez détailler votre question')]
    private ?string $content = null;

    #[ORM\Column]
    private ?int $rating = null;

    #[ORM\Column]
    private ?int $nbrOfResponse = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getNbrOfResponse(): ?int
    {
        return $this->nbrOfResponse;
    }

    public function setNbrOfResponse(int $nbrOfResponse): self
    {
        $this->nbrOfResponse = $nbrOfResponse;

        return $this;
    }
}
