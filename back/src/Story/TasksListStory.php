<?php

namespace App\Story;

use Zenstruck\Foundry\Story;
use App\Factory\TasksListFactory;

final class TasksListStory extends Story
{
    public function build(): void
    {
        TasksListFactory::createMany(20);
    }
}
