<?php

namespace App\Controller;

use App\Entity\RepLog;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/delete/{id}", methods={'DELETE'}, name="api_delete")
     */
    public function index(RepLog $repLog)
    {
        $em=$this->getDoctrine()->getManager();
        $em->remove($repLog);
        $em->flush();

        return new Response(null, 204);
    }
}
