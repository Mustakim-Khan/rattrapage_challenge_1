<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use App\Repository\TasksListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use Gedmo\Mapping\Annotation\Blameable;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\UserTasksListsController;

#[ORM\Entity(repositoryClass: TasksListRepository::class)]
#[UniqueEntity('title')]
#[ApiResource(
    operations: [
        new Get(
            security:"is_granted('ROLE_ADMIN') or object.owner == user",
            normalizationContext: ['groups' => 'get:taskslist']
        ),
        new GetCollection(
            name: "user-taskslists",
            uriTemplate: "/users/{id}/taskslists",
            controller: UserTasksListsController::class,
            normalizationContext: ['groups' => 'getc:taskslist']
        ),
        new GetCollection(
            security:"is_granted('ROLE_ADMIN')",
            normalizationContext: ['groups' => 'getc:taskslist']
        ),
        new Post(
            security:"is_granted('ROLE_USER')",
            denormalizationContext: ['groups' => 'create:taskslist'],
        ),
        new Patch(
            security:"is_granted('ROLE_ADMIN') or object.owner == user",
            denormalizationContext: ['groups' => 'patch:taskslist'],
            validationContext: ['groups' => 'patchValidation']
        ),
        new Delete(
            security:"is_granted('ROLE_ADMIN') or object.owner == user",
        )
    ]
)]
class TasksList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get:taskslist', 'getc:taskslist', 'get:task', 'getc:task'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'Le titre doit contenir plus de {{ limit }} caractères',
        maxMessage: 'Le titre doit contenir moins de {{ limit }} caractères',
        groups: ['patchValidation', 'Default']
    )]
    #[Assert\NotBlank(
        message: 'Le titre ne peut pas être vide',
        groups: ['patchValidation', 'Default']
    )]
    #[Assert\NotNull(
        message: "Le titre doit être renseigné",
        groups: ['patchValidation', 'Default']
    )]
    #[Assert\Type(
        type: 'string',
        message: "Le titre n'est pas une chaine de caractères",
        groups: ['patchValidation', 'Default']
    )]
    #[Groups(['create:taskslist', 'get:taskslist', 'getc:taskslist', 'patch:taskslist', 'get:task', 'getc:task'])]
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
        message: "La description ne peut pas être vide",
        groups: ['patchValidation', 'Default']
    )]
    #[Assert\NotNull(
        message: "La description doit être renseigné",
        groups: ['patchValidation', 'Default']
    )]
    #[Assert\Type(
        type: 'string',
        message: "La description n'est pas une chaine de caractères",
        groups: ['patchValidation', 'Default']
    )]
    #[Groups(['create:taskslist', 'get:taskslist', 'getc:taskslist', 'patch:taskslist', 'get:task', 'getc:task'])]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'tasksList', targetEntity: Task::class)]
    #[Groups(['get:taskslist', 'getc:taskslist'])]
    private Collection $tasks;

    #[ORM\ManyToOne(inversedBy: 'tasksLists')]
    #[ORM\JoinColumn(nullable: false)]
    #[Blameable(on: 'create')]
    #[Groups(['get:taskslist', 'getc:taskslist'])]
    public ?User $owner = null;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setTasksList($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getTasksList() === $this) {
                $task->setTasksList(null);
            }
        }

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
}
