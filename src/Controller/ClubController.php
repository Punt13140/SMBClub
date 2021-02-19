<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClubController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(CategorieRepository $categorieRepository): Response
    {

        $categories = $categorieRepository->getHome();
        return $this->render('club/index.html.twig', [
            'categories' => $categories
        ]);
    }


    /**
     * @Route("/cat/{id}", name="index_forum")
     * @param Categorie $categorie
     * @return Response
     */
    public function forum(Categorie $categorie): Response
    {
        return $this->render('club/categorie.html.twig', [
            'categorie' => $categorie,
            'categories' => $categorie->getCategorieChilds(),
            'parent' => $categorie->getCategorieParent()
        ]);
    }
}
