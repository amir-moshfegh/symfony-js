<?php

namespace App\Controller;

use App\Entity\RepLog;
use App\Form\RepLogType;
use App\Repository\RepLogRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $logRepository;
    private $userRepository;

    public function __construct(RepLogRepository $logRepository, UserRepository $userRepository)
    {
        $this->logRepository = $logRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/", name="home")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(RepLogType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var RepLog $rep */
            $rep = $form->getData();
            dump('ok');
            $rep->setAuthor($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($rep);
            $em->flush();

            $this->addFlash('success', 'create new successful!');
            return $this->redirectToRoute('home');
        }

        return $this->render('home/index.html.twig', [
            'logs' => $this->logRepository->findBy(['author' => $this->getUser()]),
            'sumLogs' => $this->logRepository->findSumWeightLiftedByUser($this->getUser()),
            'leaderboard' => $this->getLeaderboard(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return array
     */
    private function getLeaderboard(): array
    {
        $items = $this->logRepository->findSumWeightLiftedAllUser();

        $array = [];
        foreach ($items as $item) {
            $array[] = [
                'user' => $this->userRepository->findOneBy(['id' => $item['userId']]),
                'weight' => $item['weight'],
                'in_cats' => number_format($item['weight'] / RepLog::WEIGHT_FAT_CAT),
            ];
        }

        return $array;
    }
}
