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
use ApiPlatform\Metadata\Put;
use Gedmo\Mapping\Annotation\Blameable;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            security:"is_granted('ROLE_ADMIN') or object.owner == user",
            normalizationContext: ['groups' => 'get:task']
        ),
        new GetCollection(
            security:"is_granted('ROLE_ADMIN')",
            normalizationContext: ['groups' => 'getc:task']
        ),
        new Post(
            security:"is_granted('ROLE_ADMIN') or object.owner == user",
            denormalizationContext: ['groups' => 'create:task'],
        ),
        new Patch(
            security:"is_granted('ROLE_ADMIN') or object.owner == user or object.createdBy == user",
            denormalizationContext: ['groups' => 'patch:task'],
            validationContext: ['groups' => 'patchValidation']
        )
    ]
)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get:task', 'getc:task'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'Le titre doit contenir plus de {{ limit }} caractères',
        maxMessage: 'Le titre doit contenir moins de {{ limit }} caractères',
    )]
    #[Assert\NotBlank(groups: ['patchValidation'])]
    #[Assert\Type(
        type: 'string',
        message: "Le titre n'est pas une chaine de caractères",
        groups: ['patchValidation'] 
    )]
    #[Groups(['create:task', 'get:task', 'getc:task', 'patch:task'])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'La description doit contenir plus de {{ limit }} caractères',
        maxMessage: 'La description doit contenir moins de {{ limit }} caractères',
        groups: ['patchValidation']
    )]
    #[Assert\NotBlank(groups: ['patchValidation'])]
    #[Assert\Type(
        type: 'string',
        message: "La description n'est pas une chaine de caractères",
        groups: ['patchValidation'] 
    )]
    #[Groups(['create:task', 'get:task', 'getc:task', 'patch:task'])]
    private ?string $desciption = null;

    #[ORM\Column]
    #[Assert\NotBlank(groups: ['patchValidation'])]
    #[Assert\Type(
        type: 'integer',
        message: "La priorité n'est pas valide" ,
        groups: ['patchValidation']
    )]
    #[Assert\Range(
        min: 1,
        max: 3,
        notInRangeMessage: 'La valeur ne correspond à aucun des choix possibles',
        groups: ['patchValidation']
    )]
    #[Groups(['create:task', 'get:task', 'getc:task', 'patch:task'])]
    private ?int $priority = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(groups: ['patchValidation'])]
    #[Assert\GreaterThanOrEqual('today', message: "La date ne peut être antérieur à celle d'ajourd'hui", groups: ['patchValidation'])]
    #[Groups(['create:task', 'get:task', 'getc:task', 'patch:task'])]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[Groups(['get:task', 'getc:task'])]
    private ?TasksList $tasksList = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Groups(['create:task', 'get:task', 'getc:task'])]
    public ?User $owner = null;

    #[ORM\ManyToOne(inversedBy: 'createdTasks')]
    #[ORM\JoinColumn(nullable: false)]
    #[Blameable(on: 'create')]
    #[Groups(['get:task', 'getc:task'])]
    public ?User $createdBy = null;

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

    public function getTasksList(): ?TasksList
    {
        return $this->tasksList;
    }

    public function setTasksList(?TasksList $tasksList): static
    {
        $this->tasksList = $tasksList;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }
}
