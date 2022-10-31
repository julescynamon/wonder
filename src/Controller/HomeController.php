<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Question;
use App\Repository\QuestionRepository;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(QuestionRepository $questionRepo): Response
    {

        $questions = $questionRepo->getQuestionsWithAuthor();

        return $this->render('home/index.html.twig', [
            'questions' => $questions,
        ]);
    }
}
