<?php

namespace App\Controller;

use App\Form\RecipeType;
use App\Entity\Chefs;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use  Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

use App\Entity\Recipes;
use App\Entity\Status;
use Doctrine\Migrations\Version\State;

class RecipesController extends AbstractController
{
    # Index -> homePage
    #[Route('/recipes', name: 'recipes')]
    public function index(ManagerRegistry $doctrine): Response
    {


        $recipes = $doctrine->getRepository(Recipes::class)->findAll();
        $status = array();
        foreach ($recipes as $val) {
            array_push($status, $val->getFkStatus()->getName());
        }
        // $fk_status = "";
        // if ($recipes->'fk_status' == null) {
        //     $fk_status = "not declared";
        // } else if ($recipes['fk_status_id'] == 1) {
        //     $fk_status = "want to cook";
        // } else {
        //     $fk_status = "already cooked";
        // }
        // dd($recipes);
        return $this->render('recipes/index.html.twig', [
            'recipes' => $recipes,
            'status' => $status,

        ]);
    }

    # Create 
    #[Route('/create', name: 'create')]
    public function create(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        $recipe = new Recipes();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            $recipe = $form->getData();
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();
                try {
                    $image->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $recipe->setImage($newFilename);
            }
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
        $fk_chef = $recipes->getFkChef()->getId();
        $chef = $doctrine->getRepository(Chefs::class)->find($fk_chef);
        return $this->render('recipes/details.html.twig', [
            'recipes' => $recipes,
            'chef' => $chef,
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
