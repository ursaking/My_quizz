<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\User1Type;
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
     * @Route("/", name="user_index", methods="GET")
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', ['users' => $userRepository->findAll()]);
    }

    /**
     * @Route("/new", name="user_new", methods="GET|POST")
     */
    function new (Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer): Response {
        $user = new User();
        $form = $this->createForm(User1Type::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $name = $form->get('username')->getData();
            $mail = $form->get('mail')->getData();
            $message = (new \Swift_Message('Hello Email'))
                ->setFrom('teddyrhim052@gmail.com')
                ->setTo($mail)
                ->setBody(
                    $this->renderView(
                        'emails/registration.html.twig',
                        array('name' => $name)
                    ),
                    'text/html'
                );
            $mailer->send($message);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods="GET")
     */
    public function show(User $user, UserRepository $userRepository): Response
    {
        $user1 = $this->getUser()->getId();
        $user2 = $user->getId();
        $admin = $this->getUser()->getRoles();
        if ($user1 === $user2 || $admin[0] == 'ROLE_ADMIN') {
            return $this->render('user/show.html.twig', ['user' => $user]);
        }
        return $this->render('user/index.html.twig', ['users' => $userRepository->findAll()]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods="GET|POST")
     */
    public function edit(Request $request, User $user, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer): Response
    {
        $user1 = $this->getUser()->getId();
        $user2 = $user->getId();
        $admin = $this->getUser()->getRoles();
        $form = $this->createForm(User1Type::class, $user);
        //dd($request);
        $form->handleRequest($request);
        if ($user1 === $user2 || $admin[0] == 'ROLE_ADMIN') {
            if ($form->isSubmitted() && $form->isValid()) {
                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
                $this->getDoctrine()->getManager()->flush();
                $name = $form->get('username')->getData();
                $mail = $form->get('mail')->getData();
                $message = (new \Swift_Message('Hello Email'))
                    ->setFrom('teddyrhim052@gmail.com')
                    ->setTo($mail)
                    ->setBody(
                        $this->renderView(
                            'emails/registration.html.twig',
                            array('name' => $name)
                        ),
                        'text/html'
                    );
                $mailer->send($message);
                return $this->redirectToRoute('user_index', ['id' => $user->getId()]);
            }
            return $this->render('user/edit.html.twig', [
                'user' => $user,
                'form' => $form->createView(),
            ]);
        }
        return $this->render('user/index.html.twig', ['users' => $userRepository->findAll()]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods="DELETE")
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
