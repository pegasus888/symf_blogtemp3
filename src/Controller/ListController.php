<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ListController extends AbstractController
{
    #[Route('/list', name: 'app_list', defaults: ['name' => null], methods:['GET', 'HEAD'])]
    public function index(): Response
    {
        $students = ["Student1", "Student2", "Student3"];

        return $this->render('list/index.html.twig', array(
            'students' => $students
        ));
    }
}
