<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Category;
use App\Entity\Topic;
use App\Entity\User;
use App\Form\AnswerType;
use App\Form\TopicType;
use App\Repository\CategoryRepository;
use App\Repository\TopicRepository;
use App\Repository\UserRepository;
use App\Service\CategoryService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
     * @Route("/cat/{id_cat}", name="index_forum")
     * @Entity("category", expr="repository.find(id_cat)")
     * @param Category $category
     * @param TopicRepository $topicRepository
     * @param CategoryService $categoryService
     * @return Response
     */
    public function forum(Category $category, TopicRepository $topicRepository, CategoryService $categoryService): Response
    {
        return $this->render('club/category.html.twig', [
            'category' => $category,
            'categories' => $category->getCategoryChilds(),
            'parent' => $category->getCategoryParent(),
            'topics' => $topicRepository->findBy([
                'category' => $category,
                'isPinned' => false
            ]),
            'topics_announce' => $topicRepository->findBy([
                'category' => $category,
                'isPinned' => true
            ]),
            'build_tree' => $categoryService->buildTreeLink($category)
        ]);
    }


    /**
     * @Route("/cat/{id_cat}/topic/{id_topic}", name="topic_show", methods={"GET", "POST"})
     * @Entity("category", expr="repository.find(id_cat)")
     * @Entity("topic", expr="repository.find(id_topic)")
     * @param Request $request
     * @param Category $category
     * @param Topic $topic
     * @param UserRepository $userRepository
     * @param CategoryService $categoryService
     * @return Response
     */
    public function showTopic(Request $request, Category $category, Topic $topic, UserRepository $userRepository, CategoryService $categoryService): Response
    {
        $return_arr = [
            'topic' => $topic,
            'build_tree' => $categoryService->buildTreeLink($topic->getCategory())
        ];

        if (!$topic->getIsPinned()) {
            $answer = new Answer($this->getUser(), $topic);
            $form = $this->createForm(AnswerType::class, $answer);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $answer->setPostedAt(new \DateTime());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($answer);
                $entityManager->flush();

                //@TODO success flash
                $answer = new Answer($this->getUser(), $topic);

                $form = $this->createForm(AnswerType::class, $answer);
            }
            $return_arr['form'] = $form->createView();
        }

        return $this->render('topic/show.html.twig', $return_arr);
    }


    /**
     * @Route("/cat/{id_cat}/new", name="topic_new", methods={"GET", "POST"})
     * @Entity("category", expr="repository.find(id_cat)")
     * @param Request $request
     * @param Category $category
     * @return Response
     */
    public function newTopic(Request $request, Category $category): Response
    {
        $topic = new Topic($category, $this->getUser());
        $form = $this->createForm(TopicType::class, $topic, ['hasModeratorAuthorization' => $this->isGranted(User::$roleModerator)]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $topic->setCreatedAt(new \DateTime());
            $topic->setCreatedBy($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($topic);
            $entityManager->flush();

            return $this->redirectToRoute('topic_show', [
                'id_cat' => $category->getId(),
                'id_topic' => $topic->getId()
            ]);
        }

        return $this->render('topic/new.html.twig', [
            'category' => $category,
            'topic' => $topic,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/cat/{id_cat}/topic/{id_topic}/edit", name="topic_edit", methods={"GET", "POST"})
     * @Entity("category", expr="repository.find(id_cat)")
     * @Entity("topic", expr="repository.find(id_topic)")
     * @param Request $request
     * @param Category $category
     * @param Topic $topic
     * @return Response
     */
    public function editTopic(Request $request, Category $category, Topic $topic): Response
    {
        if (!$this->isGranted('ROLE_MODO') || $topic->getCreatedBy() !== $this->getUser()) {
            throw new AccessDeniedException();
        }
        $form = $this->createForm(TopicType::class, $topic, ['hasModeratorAuthorization' => $this->isGranted(User::$roleModerator)]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $topic->setEditedAt(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('topic_show', [
                'id_cat' => $category->getId(),
                'id_topic' => $topic->getId()
            ]);
        }

        return $this->render('topic/edit.html.twig', [
            'topic' => $topic,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="topic_delete", methods={"DELETE"})
     */
    public function deleteTopic(Request $request, Topic $topic): Response
    {
        $category = $topic->getCategory();
        if ($this->isCsrfTokenValid('delete' . $topic->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($topic);
            $entityManager->flush();
        }

        return $this->redirectToRoute('index_forum', [
            'id_cat' => $category->getId()
        ]);
    }


    /**
     * @Route("/{id}/edit", name="answer_edit", methods={"GET","POST"})
     * @Route("/cat/{id_cat}/topic/{id_topic}/answer/{id_answer}/edit", name="answer_edit", methods={"GET", "POST"})
     * @Entity("category", expr="repository.find(id_cat)")
     * @Entity("topic", expr="repository.find(id_topic)")
     * @Entity("answer", expr="repository.find(id_answer)")
     * @param Request $request
     * @param Category $category
     * @param Topic $topic
     * @param Answer $answer
     * @return Response
     */
    public function editAnswer(Request $request, Category $category, Topic $topic, Answer $answer): Response
    {
        $form = $this->createForm(AnswerType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $answer->setEditedAt(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('topic_show', [
                'id_cat' => $category->getId(),
                'id_topic' => $topic->getId()
            ]);
        }

        return $this->render('answer/edit.html.twig', [
            'answer' => $answer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="answer_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Answer $answer): Response
    {
        if ($this->isCsrfTokenValid('delete' . $answer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($answer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('topic_show', [
            'id' => $answer->getTopic()->getId()
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
