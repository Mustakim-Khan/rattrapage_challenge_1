<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Patch()
    ]
)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'Le titre doit contenir plus de {{ limit }} caractères',
        maxMessage: 'Le titre doit contenir moins de {{ limit }} caractères',
    )]
    #[Assert\NotBlank]
    #[Assert\Type(
        type: 'string',
        message: "Le title n'est pas une chaine de caractères" 
    )]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'La desciption doit contenir plus de {{ limit }} caractères',
        maxMessage: 'La desciption doit contenir moins de {{ limit }} caractères',
    )]
    #[Assert\NotBlank]
    #[Assert\Type(
        type: 'string',
        message: "La desciption n'est pas une chaine de caractères" 
    )]
    private ?string $desciption = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Type(
        type: 'integer',
        message: "La desciption n'est pas valide" 
    )]
    #[Assert\Range(
        min: 1,
        max: 3,
        notInRangeMessage: 'La valeur ne correspond à aucun des choix possibles',
    )]
    private ?int $priority = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual('today', message: "La date ne peut être antérieur à celle d'ajourd'hui")]
    private ?\DateTimeInterface $endDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDesciption(): ?string
    {
        return $this->desciption;
    }

    public function setDesciption(string $desciption): static
    {
        $this->desciption = $desciption;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }
}
