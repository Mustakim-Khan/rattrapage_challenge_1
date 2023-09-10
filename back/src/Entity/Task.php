<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use Gedmo\Mapping\Annotation\Blameable;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Controller\UserTasksController;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            security:"is_granted('ROLE_ADMIN') or object.owner == user",
            normalizationContext: ['groups' => 'get:task']
        ),
        new GetCollection(
            name: "user-tasks",
            uriTemplate: "/users/{id}/tasks",
            controller: UserTasksController::class,
            normalizationContext: ['groups' => 'getc:task']
        ),
        new GetCollection(
            security:"is_granted('ROLE_ADMIN')",
            normalizationContext: ['groups' => 'getc:task']
        ),
        new Post(
            security:"is_granted('ROLE_USER')",
            denormalizationContext: ['groups' => 'create:task'],
        ),
        new Patch(
            security:"is_granted('ROLE_ADMIN') or object.owner == user or object.createdBy == user",
            denormalizationContext: ['groups' => 'patch:task'],
            validationContext: ['groups' => 'patchValidation']
        ),
        new Delete(
            security:"is_granted('ROLE_ADMIN') or object.owner == user or object.createdBy == user",
        )
    ]
)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get:task', 'getc:task', 'get:taskslist', 'getc:taskslist'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'Le titre doit contenir plus de {{ limit }} caractères',
        maxMessage: 'Le titre doit contenir moins de {{ limit }} caractères',
        groups: ['patchValidation', 'Default']
    )]
    #[Assert\NotBlank(
        message: 'Le titre ne peut pas être vide',
        groups: ['patchValidation', 'Default'])
    ]
    #[Assert\NotNull(
        message: "Le titre doit être renseigné",
        groups: ['patchValidation', 'Default'])
    ]
    #[Assert\Type(
        type: 'string',
        message: "Le titre n'est pas une chaine de caractères",
        groups: ['patchValidation', 'Default'] 
    )]
    #[Groups(['create:task', 'get:task', 'getc:task', 'patch:task', 'get:taskslist', 'getc:taskslist'])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'La description doit contenir plus de {{ limit }} caractères',
        maxMessage: 'La description doit contenir moins de {{ limit }} caractères',
        groups: ['patchValidation', 'Default']
    )]
    #[Assert\NotBlank(
        message: 'La description ne peut pas être vide',
        groups: ['patchValidation', 'Default'])
    ]
    #[Assert\NotNull(
        message: "La description doit être renseigné",
        groups: ['patchValidation', 'Default'])
    ]
    #[Assert\Type(
        type: 'string',
        message: "La description n'est pas une chaine de caractères",
        groups: ['patchValidation', 'Default'] 
    )]
    #[Groups(['create:task', 'get:task', 'getc:task', 'patch:task', 'get:taskslist', 'getc:taskslist'])]
    private ?string $desciption = null;

    #[ORM\Column]
    #[Assert\NotBlank(
        message: 'La priorité ne peut pas être vide',
        groups: ['patchValidation', 'Default'])
    ]
    #[Assert\NotNull(
        message: "La priorité doit être renseigné",
        groups: ['patchValidation', 'Default'])
    ]
    #[Assert\Type(
        type: 'integer',
        message: "La priorité n'est pas valide" ,
        groups: ['patchValidation', 'Default']
    )]
    #[Assert\Range(
        min: 1,
        max: 3,
        notInRangeMessage: 'La valeur ne correspond à aucun des choix possibles',
        groups: ['patchValidation', 'Default']
    )]
    #[Groups(['create:task', 'get:task', 'getc:task', 'patch:task', 'get:taskslist', 'getc:taskslist'])]
    private ?int $priority = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(
        message: "La date d'écheance ne peut pas être vide",
        groups: ['patchValidation', 'Default'])
    ]
    #[Assert\NotNull(
        message: "La date d'écheance doit être renseigné",
        groups: ['patchValidation', 'Default'])
    ]
    #[Assert\GreaterThanOrEqual(
        'today', 
        message: "La date ne peut être antérieur à celle d'ajourd'hui", 
        groups: ['patchValidation', 'Default'])
    ]
    #[Groups(['create:task', 'get:task', 'getc:task', 'patch:task', 'get:taskslist', 'getc:taskslist'])]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[Groups(['get:task', 'getc:task'])]
    private ?TasksList $tasksList = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[Assert\NotBlank(
        message: "L'utilisateur affecté ne peut pas être vide",
        groups: ['Default'])
    ]
    #[Assert\NotNull(
        message: "L'utilisateur affecté doit être renseigné",
        groups: ['Default'])
    ]
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
