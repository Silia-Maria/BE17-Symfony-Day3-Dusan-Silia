<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Recipes;
use App\Entity\Chefs;
use App\Entity\Status;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


#[Route('/user')]
class UserController extends AbstractController
{
    # HomePage for User
    #[Route('/', name: 'app_user')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $recipes = $doctrine->getRepository(Recipes::class)->findAll();
        $status = array();
        foreach ($recipes as $val) {
            array_push($status, $val->getFkStatus()->getName());
        }

        return $this->render('user/index.html.twig', [
            'recipes' => $recipes,
            'status' => $status,

        ]);
    }

    # Details Page for User
    #[Route('/details/{id}', name: 'details_user')]
    public function detailsUser(ManagerRegistry $doctrine, $id): Response
    {
        $recipes = $doctrine->getRepository(Recipes::class)->find($id);
        $fk_chef = $recipes->getFkChef()->getId();
        $chef = $doctrine->getRepository(Chefs::class)->find($fk_chef);
        return $this->render('user/details_user.html.twig', [
            'recipes' => $recipes,
            'chef' => $chef,
        ]);
    }

    # If button already cooked is clicked:
    #[Route('/update-status/{id}', name: 'update-status')]
    public function alreadyCooked($id, ManagerRegistry $doctrine)
    {
        $recipe = $doctrine->getRepository(Recipes::class)->find($id);
        $status = $doctrine->getRepository(Status::class)->find(2);
        $recipe->setFkStatus($status);
        $em = $doctrine->getManager();
        $em->persist($recipe);
        $em->flush();
        return $this->redirectToRoute('app_user');
    }
}
