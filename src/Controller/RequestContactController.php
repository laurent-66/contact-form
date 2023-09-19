<?php

namespace App\Controller;

use App\Form\RequestContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    private $entityManager;

    public function __construct(
    EntityManagerInterface $entityManager,
    RequestContactRepository $requestContactRepository,
    ContactRepository $contactRepository,)
    {
        $this->requestContactRepository = $requestContactRepository;
        $this->contactRepository = $contactRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/admin/contacts/{id}/validationRequests', name: 'validation_request')]
    public function updateValidationRequest( Request $request, $id): Response
    {

        $requestContacts = $this->requestContactRepository->findByContact($id);

        foreach( $requestContacts as $question){
            $questionId = $question->getId();
            $requestContactType = new RequestContactType();
            $form = $this->createForm($requestContactType , $question);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $question = $form->getData();
                $question->setIsValidated($question->getIsValidated());
                $this->entityManager->persist($question);
            }
        }

        $this->entityManager->flush();

        return $this->render('contact/index.html.twig', [
            'form' => $form,
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
