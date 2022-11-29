<?php

namespace App\Controller;

use App\Form\RecipeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use  Symfony\Component\HttpFoundation\Request;

use App\Entity\Recipes;

class RecipesController extends AbstractController
{
    # Index -> homePage
    #[Route('/recipes', name: 'recipes')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $recipes = $doctrine->getRepository(Recipes::class)->findAll();
        return $this->render('recipes/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    # Create 
    #[Route('/create', name: 'create')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $recipe = new Recipes();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();
            $recipe->setCreateDate(new \DateTime('now'));
            $em = $doctrine->getManager();
            $em->persist($recipe);
            $em->flush();
            return $this->redirectToRoute('recipes');
        }
        return $this->render('recipes/create.html.twig', [
            "form" => $form->createView()
        ]);
    }

    # Edit 
    #[Route('/edit/{id}', name: 'edit')]
    public function edit($id, Request $request, ManagerRegistry $doctrine): Response
    {
        $recipe = $doctrine->getRepository(Recipes::class)->find($id);
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();
            $recipe->setCreateDate(new \DateTime('now'));
            $em = $doctrine->getManager();
            $em->persist($recipe);
            $em->flush();
            return $this->redirectToRoute('recipes');
        }
        return $this->render('recipes/edit.html.twig', [
            "form" => $form->createView()
        ]);
    }

    # Details 
    #[Route('/details/{id}', name: 'details')]
    public function details(ManagerRegistry $doctrine, $id): Response
    {
        $recipes = $doctrine->getRepository(Recipes::class)->find($id);
        return $this->render('recipes/details.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    # Delete 
    #[Route('/delete/{id}', name: 'delete')]
    public function delete($id, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $recipe = $doctrine->getRepository(Recipes::class)->find($id);
        $em->remove($recipe);
        $em->flush();
        return $this->redirectToRoute('recipes');
    }
}
