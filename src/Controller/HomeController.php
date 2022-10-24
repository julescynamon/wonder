<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $questions = [
            [
                'title' => 'How to create a Symfony project1?',
                'content' => 'I want to create a Symfony project, but I don\'t know how to do it.',
                'rating' => 20,
                'author' => [
                    'name' => 'Jean Dupont',
                    'avatar' => 'https://randomuser.me/api/portraits/men/10.jpg'
                ],
                'nbrOfResponse' => 15,
            ],
            [
                'title' => 'How to create a Symfony project2?',
                'content' => 'I want to create a Symfony project, but I don\'t know how to do it.',
                'rating' => 0,
                'author' => [
                    'name' => 'Jean Dupont',
                    'avatar' => 'https://randomuser.me/api/portraits/women/44.jpg'
                ],
                'nbrOfResponse' => 15,
            ],
            [
                'title' => 'How to create a Symfony project3?',
                'content' => 'I want to create a Symfony project, but I don\'t know how to do it.',
                'rating' => -15,
                'author' => [
                    'name' => 'Jean Dupont',
                    'avatar' => 'https://randomuser.me/api/portraits/men/88.jpg'
                ],
                'nbrOfResponse' => 15,
            ],
        ];

        return $this->render('home/index.html.twig', [
            'questions' => $questions,
        ]);
    }
}
