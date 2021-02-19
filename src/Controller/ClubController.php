<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClubController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->getHome();
        return $this->render('club/index.html.twig', [
            'categories' => $categories
        ]);
    }


    /**
     * @Route("/cat/{id}", name="index_forum")
     * @param Category $category
     * @return Response
     */
    public function forum(Category $category): Response
    {
        return $this->render('club/category.html.twig', [
            'category' => $category,
            'categories' => $category->getCategoryChilds(),
            'parent' => $category->getCategoryParent(),
            'topics' => $category->getTopics()
        ]);
    }
}
