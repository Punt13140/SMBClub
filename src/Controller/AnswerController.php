<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Topic;
use App\Form\AnswerType;
use App\Repository\AnswerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/answer")
 */
class AnswerController extends AbstractController
{
    /**
     * @Route("/new/{id}", name="answer_new", methods={"GET","POST"})
     * @param Request $request
     * @param Topic $topic
     * @return Response
     * public function new(Request $request, Topic $topic): Response
     * {
     * $answer = new Answer($this->getUser(), $topic);
     * $form = $this->createForm(AnswerType::class, $answer);
     * $form->handleRequest($request);
     *
     * if ($form->isSubmitted() && $form->isValid()) {
     * $answer->setPostedAt(new \DateTime());
     * $entityManager = $this->getDoctrine()->getManager();
     * $entityManager->persist($answer);
     * $entityManager->flush();
     *
     * return $this->redirectToRoute('topic_show', [
     * 'id' => $topic->getId()
     * ]);
     * }
     *
     * return $this->render('answer/new.html.twig', [
     * 'answer' => $answer,
     * 'form' => $form->createView(),
     * ]);
     * }
     */

}
