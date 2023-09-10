<?php
namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsController]
class UserTasksController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository
    ) {}
    public function __invoke(string $id): Collection
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw new NotFoundHttpException("User not found");
        }
        return $user->getTasks();
    }
}