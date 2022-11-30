<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChefController extends AbstractController
{
    #[Route('/chef', name: 'app_chef')]
    public function index(): Response
    {
        return $this->render('chef/index.html.twig', [
            'controller_name' => 'ChefController',
        ]);
    }
}
