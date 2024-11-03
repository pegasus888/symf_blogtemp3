<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StudentsController extends AbstractController
{
    #[Route('/students/{name}', name: 'app_students', defaults: ['name' => null], methods:['GET', 'HEAD'])]
    public function index($name): Response
    {
        return $this->render('students/index.html.twig', [
            'name' => 'Noémie',
            'message' => $name,
            'controller_name' => 'StudentsController',
        ]);
    }
}
