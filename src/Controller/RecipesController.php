<?php

namespace App\Controller;

use App\Entity\Recipes;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class RecipesController extends AbstractController
{
    # Index -> homePage
    #[Route('/recipes', name: 'app_recipes')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $recipes = $doctrine->getRepository(Recipes::class)->findAll();
        return $this->render('recipes/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    # Create
    #[Route('/create', name: 'create')]
    public function create(): Response
    {
        return $this->render('recipes/create.html.twig', []);
    }

    # Edit
    #[Route('/edit/{id}', name: 'edit')]
    public function edit($id): Response
    {
        return $this->render('recipes/edit.html.twig', []);
    }

    # Details
    #[Route('/details/{id}', name: 'details')]
    public function details($id): Response
    {
        return $this->render('recipes/index.html.twig', []);
    }

    # Delete
    #[Route('/delete/{id}', name: 'delete')]
    public function delete($id, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $recipe = $doctrine->getRepository(Recipes::class)->find($id);
        $em->remove($recipe);
        $em->flush();
        return $this->redirectToRoute('index');
    }
}
