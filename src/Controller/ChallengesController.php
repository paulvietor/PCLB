<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Challenge;

class ChallengesController extends AbstractController
{
    /**
     * @Route("/challenges", name="challenges")
     */
    public function index(): Response
    {
        $repo = $this->getDoctrine()->getRepository(Challenge::class);

        $challenges = $repo->findAll();

        usort($challenges, function ($c1, $c2) {
            return $c1->getDifficulty() - $c2->getDifficulty();
        });

        return $this->render('pages/challenges.html.twig', [
            'challenges' => $challenges
        ]);
    }
}
