<?php

namespace App\Controller;

use App\Entity\Recipes;
use App\Entity\Chefs;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class ChefsController extends AbstractController
{
    #[Route('/chefs/{id}', name: 'app_chefs')]
    public function index(ManagerRegistry $doctrine, $id): Response
    {

        $chef = $doctrine->getRepository(Chefs::class)->find($id);
        $recipes = $doctrine->getRepository(Recipes::class)->findBy(["fk_chef" => $id]);

        return $this->render('chefs/index.html.twig', [
            'chef' => $chef,
            'recipes' => $recipes,
        ]);
    }
}
