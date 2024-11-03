<?php

namespace App\Controller;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UsersController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/users', name: 'app_users')]
    public function index(): Response
    {
        // findAll - SELECT * FROM users;
        $repository = $this->em->getRepository(Users::class);
        $users = $repository->findAll();

        return $this->render('users/index.html.twig', [
            'users' => $users
//            'controller_name' => 'UsersController',
        ]);
    }
}
