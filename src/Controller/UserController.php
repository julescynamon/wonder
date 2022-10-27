<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class UserController extends AbstractController
{
    #[Route('/user/{id}', name: 'user')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function userProfile(User $user): Response
    {
        $currentUser = $this->getUser();

        if ($currentUser === $user) {
            return $this->redirectToRoute('current_user');
        }

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/user', name: 'current_user')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function currentUserProfile(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'Page de profile de l\'utilisateur connecté',
        ]);
    }
}
