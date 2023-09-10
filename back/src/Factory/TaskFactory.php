<?php

namespace App\Factory;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;
use App\Factory\UserFactory;

/**
 * @extends ModelFactory<Task>
 *
 * @method        Task|Proxy                     create(array|callable $attributes = [])
 * @method static Task|Proxy                     createOne(array $attributes = [])
 * @method static Task|Proxy                     find(object|array|mixed $criteria)
 * @method static Task|Proxy                     findOrCreate(array $attributes)
 * @method static Task|Proxy                     first(string $sortedField = 'id')
 * @method static Task|Proxy                     last(string $sortedField = 'id')
 * @method static Task|Proxy                     random(array $attributes = [])
 * @method static Task|Proxy                     randomOrCreate(array $attributes = [])
 * @method static TaskRepository|RepositoryProxy repository()
 * @method static Task[]|Proxy[]                 all()
 * @method static Task[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Task[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Task[]|Proxy[]                 findBy(array $attributes)
 * @method static Task[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Task[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class TaskFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'createdBy' => UserFactory::random(),
            'desciption' => self::faker()->text(100),
            'endDate' => self::faker()->dateTimeInInterval('now', '+1 week'),
            'priority' => self::faker()->numberBetween(1, 3),
            'title' => self::faker()->text(10),
            'owner' => UserFactory::random()
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Task $task): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Task::class;
    }
}
