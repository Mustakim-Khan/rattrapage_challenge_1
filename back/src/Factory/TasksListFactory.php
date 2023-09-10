<?php

namespace App\Factory;

use App\Entity\TasksList;
use App\Repository\TasksListRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<TasksList>
 *
 * @method        TasksList|Proxy                     create(array|callable $attributes = [])
 * @method static TasksList|Proxy                     createOne(array $attributes = [])
 * @method static TasksList|Proxy                     find(object|array|mixed $criteria)
 * @method static TasksList|Proxy                     findOrCreate(array $attributes)
 * @method static TasksList|Proxy                     first(string $sortedField = 'id')
 * @method static TasksList|Proxy                     last(string $sortedField = 'id')
 * @method static TasksList|Proxy                     random(array $attributes = [])
 * @method static TasksList|Proxy                     randomOrCreate(array $attributes = [])
 * @method static TasksListRepository|RepositoryProxy repository()
 * @method static TasksList[]|Proxy[]                 all()
 * @method static TasksList[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static TasksList[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static TasksList[]|Proxy[]                 findBy(array $attributes)
 * @method static TasksList[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static TasksList[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class TasksListFactory extends ModelFactory
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
            'description' => self::faker()->text(100),
            'owner' => UserFactory::random(),
            'title' => self::faker()->text(15),
            'tasks' => TaskFactory::randomSet(3, [])
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(TasksList $tasksList): void {})
        ;
    }

    protected static function getClass(): string
    {
        return TasksList::class;
    }
}
