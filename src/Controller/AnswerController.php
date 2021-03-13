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
     */
    public function new(Request $request, Topic $topic): Response
    {
        $answer = new Answer($this->getUser(), $topic);
        $form = $this->createForm(AnswerType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $answer->setPostedAt(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($answer);
            $entityManager->flush();

            return $this->redirectToRoute('topic_show', [
                'id' => $topic->getId()
            ]);
        }

        return $this->render('answer/new.html.twig', [
            'answer' => $answer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="answer_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Answer $answer
     * @return Response
     */
    public function edit(Request $request, Answer $answer): Response
    {
        $form = $this->createForm(AnswerType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('topic_show', [
                'id' => $answer->getTopic()->getId()
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
}
