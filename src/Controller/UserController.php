<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePwdFormType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/profil", name="user_profil")
     */
    public function showProfil(): Response
    {
        return $this->render('user/show.html.twig');
    }

    /**
     * @Route("/edit", name="user_edit")
     */
    public function edit(Request $request, UserRepository $repository): Response
    {
        $user = $repository->findOneBy(['email' => $this->getUser()->getUsername()]);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Modifications sauvegardées.');
            return $this->redirectToRoute('user_profil');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit_pwd", name="user_edit_pwd")
     */
    public function editPassword(Request $request, UserRepository $repository, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $repository->findOneBy(['email' => $this->getUser()->getUsername()]);
        $form = $this->createForm(ChangePwdFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$passwordEncoder->isPasswordValid($user, $form->get('plainPasswordOld')->getData())) {

                dump('ici');
                return $this->render('user/edit.html.twig', [
                    'user' => $user,
                    'form' => $form->createView(),
                    'error_msg' => 'Erreur sur l\'ancien mot de passe.'
                ]);
            }

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPasswordNew')->getData()
                )
            );

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Mot de passe modifié.');
            return $this->redirectToRoute('user_profil');
        }

        return $this->render('user/edit_pwd.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
