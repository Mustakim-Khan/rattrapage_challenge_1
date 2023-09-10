<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Story\UserStory;
use App\Story\TaskStory;
use App\Story\TasksListStory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        // $manager->flush();
        UserStory::load();
        TaskStory::load();
        TasksListStory::load();
    }
}
