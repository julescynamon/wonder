<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SecurityController extends AbstractController
{
    #[Route('/signup', name: 'signup')]
    public function signup(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer): Response
    {
        $user = new User();
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Votre compte a bien été créé');
            $email = new TemplatedEmail();
            $email->to($user->getEmail());
            $email->subject('Bienvenue sur Wonder');
            $email->htmlTemplate('@email_templates/welcome.html.twig');
            $email->context([
                'username' => $user->getFirstname()
            ]);
            $mailer->send($email);
            return $this->redirectToRoute('login');
        }

        return $this->render('security/signup.html.twig', [
            'form' => $userForm->createView()
        ]);
    }

    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $username = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'username' => $username,
            'error' => $error
        ]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout()
    {
    }
}
