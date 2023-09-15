<?php

namespace App\Controller;

use App\Form\RequestContactType;
use App\Repository\ContactRepository;
use App\Repository\RequestContactRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RequestContactController extends AbstractController
{
    private $request;
    private $requestContactRepository;
    private $contactRepository;

    public function __construct(RequestContactRepository $requestContactRepository,ContactRepository $contactRepository,)
    {
        $this->requestContactRepository = $requestContactRepository;
        $this->contactRepository = $contactRepository;
    }

    #[Route('/admin/contacts/{id}/validationRequests', name: 'validation_request')]
    public function updateValidationRequest(Request $request, $id): Response
    {
        $formData = $request->request->all();
        $contact = $this->contactRepository->find($id);

        // Parcourez les données pour récupérer les valeurs des cases à cocher cochées
        $checkedOptions = [];
        foreach ($formData as $key => $value) {
            if (strpos($key, 'isValidated') === 0 && $value === '1') {
                $checkedOptions[] = substr($key, 9); // Pour obtenir l'ID de l'option
            }
        }
        $form = $this->createForm(RequestContactType::class);

        return $this->renderForm('contact/index.html.twig', [
            'form' => $form,
            'contact' => $contact,
        ]);




        // dd($formData);

        // $requestContact = $this->requestContactRepository->getRequestAll($id);

        // $form = $this->createForm(RequestContactType::class);

        // $form->handleRequest($request);

        // dd($form->handleRequest($request)->getData());


        // if ($form->isSubmitted() && $form->isValid()) {
        //     $requestContact = $form->getData();
        //     dd($requestContact);

        //     // ... perform some action, such as saving the task to the database

        //     return $this->redirectToRoute('task_success');
        // }

        // return $this->renderForm('task/new.html.twig', [
        //     'form' => $form,
        // ]);
    }
}
