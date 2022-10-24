<?php

namespace App\Controller;

use App\Form\QuestionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class QuestionController extends AbstractController
{
    #[Route('/question/ask', name: 'question_form')]
    public function index(Request $request): Response
    {

        $formQuestion = $this->createForm(QuestionType::class);

        $formQuestion->handleRequest($request);

        if ($formQuestion->isSubmitted() && $formQuestion->isValid()) {
        }

        return $this->render('question/index.html.twig', [
            'form' => $formQuestion->createView(),
        ]);
    }

    #[Route('/question/{id}', name: 'question_show')]
    public function show(Request $request, string $id): Response
    {

        $question = [
            'title' => 'How to create a Symfony project1?',
            'content' => 'I want to create a Symfony project, but I don\'t know how to do it.',
            'rating' => 20,
            'author' => [
                'name' => 'Jean Dupont',
                'avatar' => 'https://randomuser.me/api/portraits/men/10.jpg'
            ],
            'nbrOfResponse' => 15,
        ];

        return $this->render('question/show.html.twig', [
            'question' => $question
        ]);
    }
}
