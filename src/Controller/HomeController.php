<?php

namespace App\Controller;

use App\Repository\RepLogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(RepLogRepository $repLogRepository): Response
    {

        return $this->render('home/index.html.twig', [
            'logs' => $repLogRepository->findBy(['author' => $this->getUser()]),
        ]);
    }
}
