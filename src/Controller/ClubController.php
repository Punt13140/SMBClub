<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\TopicRepository;
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
     * @param TopicRepository $topicRepository
     * @return Response
     */
    public function forum(Category $category, TopicRepository $topicRepository): Response
    {
        return $this->render('club/category.html.twig', [
            'category' => $category,
            'categories' => $category->getCategoryChilds(),
            'parent' => $category->getCategoryParent(),
            'topics' => $topicRepository->findBy([
                'category' => $category,
                'isAnnouncement' => false
            ]),
            'topics_announce' => $topicRepository->findBy([
                'category' => $category,
                'isAnnouncement' => true
            ])
        ]);
    }


    /**
     * @Route("/rules", name="rules")
     * @return Response
     */
    public function rules(): Response
    {
        return $this->render('club/rules.html.twig', []);
    }

    /**
     * @Route("/contact", name="contact")
     * @return Response
     */
    public function contact(): Response
    {
        return $this->render('club/contact.html.twig', []);
    }
}
