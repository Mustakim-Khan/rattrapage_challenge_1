<?php

namespace App\Story;

use Zenstruck\Foundry\Story;
use App\Factory\TaskFactory;

final class TaskStory extends Story
{
    public function build(): void
    {
        TaskFactory::createMany(20);
    }
}
