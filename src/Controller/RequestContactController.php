<?php

namespace App\Controller;

use App\Repository\RequestContactRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RequestContactController extends AbstractController
{

    private $request;
    private $requestContactRepository;

    public function __construct(Request $request, RequestContactRepository $requestContactRepository)
    {
        $this->request = $request;
        $this->requestContactRepository = $requestContactRepository;
    }

    #[Route('/admin/contacts/{id}/validationRequests', name: 'validation_request')]
    public function updateValidationRequest($id): Response
    {
        $requestContact = $this->requestContactRepository->getRequestAll($id);
        dd($requestContact);

        $form = $this->createForm(RequestContactType::class, $requestContact);

        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $requestContact = $form->getData();
            dd($requestContact);

            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('task_success');
        }

        return $this->renderForm('task/new.html.twig', [
            'form' => $form,
        ]);
    }
}
