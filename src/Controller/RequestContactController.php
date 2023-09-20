<?php

namespace App\Controller;

use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RequestContactRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RequestContactController extends AbstractController
{
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
        $contact = $this->contactRepository->find($id);

        $requestContacts =  $request->request;

        foreach( $requestContacts as $key => $value){
            $requestObject = $this->requestContactRepository->find($key);
            $questionValidated = $requestObject->isIsValidated();

            if($requestObject && $questionValidated === false ) {

                $requestObject->setIsValidated(true);
                $this->entityManager->persist($requestObject);
                $this->entityManager->flush();

                $contact->addRequestContact($requestObject);
                $this->entityManager->persist($contact);
                $this->entityManager->flush();

            }   

        }

        $contact = $this->contactRepository->find($id);
        $requestAll = $this->requestContactRepository->getRequestAll($id);
        $requestCompleted = count($this->requestContactRepository->getRequestCompleted($id));
        $requestToMake = count($this->requestContactRepository->getRequestToMake($id));

        return $this->render('contact/index.html.twig', [
            'contact' => $contact,
            'requestAll' => $requestAll,
            'requestCompleted' => $requestCompleted,
            'requestToMake' => $requestToMake,
        ]);
    }
}
