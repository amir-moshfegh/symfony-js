<?php

namespace App\Controller;

use App\Entity\RepLog;
use App\Form\RepLogType;
use App\services\GetFormErrors;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/delete/{id}", methods={"DELETE"}, name="api_delete")
     */
    public function index(RepLog $repLog): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($repLog);
        $em->flush();

        return new Response(null, 204);
    }


    /**
     * @Route("/api/new", methods={"POST"}, name="api_new")
     */
    public function new(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            throw new BadRequestHttpException('Invalid Json');
        }

        $form = $this->createForm(RepLogType::class, null, [
            'csrf_protection' => false,
        ]);

        $form->submit($data);
        if (!$form->isValid()) {
            $errors = $this->getErrorsFromForm($form);

            return $this->json([
                'errors' => $errors
            ], 400);
        }

        /** @var RepLog $replog */
        $replog = $form->getData();
        $replog->setAuthor($this->getUser());
        $em = $this->getDoctrine()->getManager();
        $em->persist($replog);
        $em->flush();

        return $this->json($replog, 202, [], ["groups" => "main"]);

    }


    private function getErrorsFromForm(FormInterface $form)
    {
        foreach ($form->getErrors() as $error) {
            return $error->getMessage();
        }

        $errors = [];
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childError = self::getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childError;
                }
            }
        }
        return $errors;
    }
}
