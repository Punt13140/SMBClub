<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Category;
use App\Entity\Topic;
use App\Entity\User;
use App\Form\AnswerType;
use App\Form\TopicType;
use App\Repository\TopicRepository;
use App\Repository\UserRepository;
use App\Service\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/topic")
 */
class TopicController extends AbstractController
{

    /**
     * @Route("/new/{id}", name="topic_new", methods={"GET","POST"})
     * @param Request $request
     * @param Category $category
     * @return Response
     */
    public function new(Request $request, Category $category, UserRepository $userRepository): Response
    {
        $topic = new Topic($category, $this->getUser());
        $form = $this->createForm(TopicType::class, $topic, ['hasModeratorAuthorization' => $this->isGranted(User::$roleModerator)]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $topic->setCreatedAt(new \DateTime());
            $topic->setCreatedBy($userRepository->findOneBy(['email' => $this->getUser()->getUsername()]));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($topic);
            $entityManager->flush();

            return $this->redirectToRoute('topic_show', ['id' => $topic->getId()]);
        }

        return $this->render('topic/new.html.twig', [
            'category' => $category,
            'topic' => $topic,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="topic_show", methods={"GET", "POST"})
     * @param Request $request
     * @param Topic $topic
     * @param UserRepository $userRepository
     * @param CategoryService $categoryService
     * @return Response
     */
    public function show(Request $request, Topic $topic, UserRepository $userRepository, CategoryService $categoryService): Response
    {
        $return_arr = [
            'topic' => $topic,
            'build_tree' => $categoryService->buildTreeLink($topic->getCategory())
        ];

        if (!$topic->getIsPinned()) {
            $answer = new Answer(new \DateTime(), $this->getUser(), $topic);
            $form = $this->createForm(AnswerType::class, $answer);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($answer);
                $entityManager->flush();

                //@TODO success flash
                $answer = new Answer(new \DateTime(), $this->getUser(), $topic);

                $form = $this->createForm(AnswerType::class, $answer);
            }
            $return_arr['form'] = $form->createView();
        }

        return $this->render('topic/show.html.twig', $return_arr);
    }

    /**
     * @Route("/{id}/edit", name="topic_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Topic $topic): Response
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
                'id' => $topic->getId()
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
    public function delete(Request $request, Topic $topic): Response
    {
        if ($this->isCsrfTokenValid('delete' . $topic->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($topic);
            $entityManager->flush();
        }

        return $this->redirectToRoute('topic_index');
    }
}
