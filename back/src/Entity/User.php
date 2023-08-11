<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\State\UserPasswordHasher;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity('username', message: "Nom d'utilisateur déjà existant")]
#[UniqueEntity('email', message: "Email déjà existant")]
#[ApiResource(
    operations: [
        new Get(
            security:"is_granted('ROLE_ADMIN') or object == user"
        ),
        new GetCollection(
            security:"is_granted('ROLE_ADMIN')"
        ),
        new Post(
            name: 'register-user',
            uriTemplate: '/users/register',
            denormalizationContext: ['groups' => 'create:user'],
            processor: UserPasswordHasher::class
        ),
        new Post(
            name: 'register-admin',
            uriTemplate: '/users/admin/create',
            processor: UserPasswordHasher::class,
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Patch(
            security:"is_granted('ROLE_ADMIN') or object == user"
        )
    ]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get:task', 'getc:task', 'get:taskslist', 'getc:taskslist'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 20,
        minMessage: "Le nom d'utilisateur doit contenir plus de {{ limit }} caractères",
        maxMessage: "Le nom d'utilisateur doit contenir moins de {{ limit }} caractères",
    )]
    #[Assert\Type(
        type: 'string',
        message: "Le nom d'utilisateur n'est pas une chaine de caractères" 
    )]
    #[Groups(['create:user', 'get:task', 'getc:task', 'get:taskslist', 'getc:taskslist'])]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\Length(
        min: 16,
        max: 30,
        minMessage: 'Le mot de passe doit contenir plus de {{ limit }} caractères',
        maxMessage: 'Le mot de passe doit contenir moins de {{ limit }} caractères',
    )]
    #[Assert\NotBlank]
    #[Assert\Type(
        type: 'string',
        message: "Le mot de passe n'est pas une chaine de caractères" 
    )]
    #[Groups(['create:user'])]
    #[Assert\PasswordStrength] // 16 chars, at least 1 caps, at least 1 number, at least 1 special char
    private ?string $password = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Groups(['create:user'])]
    private ?string $email = null;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Task::class)]
    private Collection $tasks;

    #[ORM\OneToMany(mappedBy: 'createdBy', targetEntity: Task::class)]
    private Collection $createdTasks;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: TasksList::class)]
    private Collection $tasksLists;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->createdTasks = new ArrayCollection();
        $this->tasksLists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

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
            $task->setOwner($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getOwner() === $this) {
                $task->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getCreatedTasks(): Collection
    {
        return $this->createdTasks;
    }

    public function addCreatedTask(Task $createdTask): static
    {
        if (!$this->createdTasks->contains($createdTask)) {
            $this->createdTasks->add($createdTask);
            $createdTask->setCreatedBy($this);
        }

        return $this;
    }

    public function removeCreatedTask(Task $createdTask): static
    {
        if ($this->createdTasks->removeElement($createdTask)) {
            // set the owning side to null (unless already changed)
            if ($createdTask->getCreatedBy() === $this) {
                $createdTask->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TasksList>
     */
    public function getTasksLists(): Collection
    {
        return $this->tasksLists;
    }

    public function addTasksList(TasksList $tasksList): static
    {
        if (!$this->tasksLists->contains($tasksList)) {
            $this->tasksLists->add($tasksList);
            $tasksList->setOwner($this);
        }

        return $this;
    }

    public function removeTasksList(TasksList $tasksList): static
    {
        if ($this->tasksLists->removeElement($tasksList)) {
            // set the owning side to null (unless already changed)
            if ($tasksList->getOwner() === $this) {
                $tasksList->setOwner(null);
            }
        }

        return $this;
    }
}
