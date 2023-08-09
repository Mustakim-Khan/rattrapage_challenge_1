<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
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
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TasksListRepository::class)]
#[UniqueEntity('title')]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Patch()
    ]
)]
class TasksList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get:task', 'getc:task'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'Le titre doit contenir plus de {{ limit }} caractères',
        maxMessage: 'Le titre doit contenir moins de {{ limit }} caractères',
    )]
    #[Assert\NotBlank]
    #[Assert\Type(
        type: 'string',
        message: "Le titre n'est pas une chaine de caractères" 
    )]
    #[Groups(['get:task', 'getc:task'])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'La description doit contenir plus de {{ limit }} caractères',
        maxMessage: 'La description doit contenir moins de {{ limit }} caractères',
    )]
    #[Assert\NotBlank]
    #[Assert\Type(
        type: 'string',
        message: "La description n'est pas une chaine de caractères" 
    )]
    #[Groups(['get:task', 'getc:task'])]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'tasksList', targetEntity: Task::class)]
    private Collection $tasks;

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
}
