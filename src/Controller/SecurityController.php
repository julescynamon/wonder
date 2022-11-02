<?php

namespace App\Controller;

use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\ResetPasswordRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

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

    #[Route('/reset-password', name: 'reset-password')]
    public function resetRoute(string $token)
    {
        return $this->json([]);
    }

    #[Route('/reset-password-request', name: 'reset-password-request')]
    public function resetPassword(Request $request, UserRepository $userRepo, ResetPasswordRepository $resetPasswordRepository, EntityManagerInterface $em, MailerInterface $mailer)
    {

        $emailForm = $this->createFormBuilder()->add('email', EmailType::class, [
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez renseigner votre email'
                ])
            ]
        ])->getForm();

        $emailForm->handleRequest($request);
        if ($emailForm->isSubmitted() && $emailForm->isValid()) {
            $emailValue = $emailForm->get('email')->getData();
            $user = $userRepo->findOneBy(['email' => $emailValue]);
            if ($user) {

                $oldResetPassword = $resetPasswordRepository->findOneBy(['user' => $user]);
                if ($oldResetPassword) {
                    $em->remove($oldResetPassword);
                    $em->flush();
                }

                $resetPassword = new ResetPassword();
                $resetPassword->setUser($user);
                $resetPassword->setExpiredAt(new \DateTimeImmutable('+2 hours'));
                $token = substr(str_replace(['+', '=', '/'], '', base64_encode(random_bytes(30))), 0, 20);
                $resetPassword->setToken($token);
                $em->persist($resetPassword);
                $em->flush();
                $email = new TemplatedEmail();
                $email->to($emailValue);
                $email->subject('Réinitialisation de votre mot de passe');
                $email->htmlTemplate('@email_templates/reset-password.html.twig');
                $email->context([
                    'token' => $token
                ]);
                $mailer->send($email);
            }

            $this->addFlash('succes', 'Un email vous à été envoyé pour réinitialiser votre mot de passe');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('security/reset-password-request.html.twig', [
            'form' => $emailForm->createView()
        ]);
    }
}
