<?php

namespace App\Controller;

use App\Form\RequestContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RequestContactRepository;
use GuzzleHttp\Psr7\ServerRequest;
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

            $form = $this->createForm(RequestContactType::class , $question);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $question = $form->getData();
                $question->setIsValidated($question->getIsValidated());
                $this->entityManager->persist($question);
                $this->entityManager->flush();
            }
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form,
        ]);
    }
}
