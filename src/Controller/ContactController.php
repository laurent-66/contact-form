<?php

namespace App\Controller;

use App\Repository\ContactRepository;
use App\Repository\RequestContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{

    private $entityManager;
    private $contactRepository;
    private $requestContactRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ContactRepository $contactRepository,
        RequestContactRepository $requestContactRepository

    ) {

        $this->entityManager = $entityManager;
        $this->contactRepository = $contactRepository;
        $this->requestContactRepository = $requestContactRepository;
    } 


    #[Route('/admin/contacts', name: 'contact-list')]
    public function contactList(): Response
    {
        $contacts = $this->contactRepository->findAll();
        $requestCompletedContact = $this->contactRepository->findAll();
        $requestToMakeContact = $this->contactRepository->findAll();

        return $this->render('contact-list/index.html.twig', [
            'contacts' => $contacts,
        ]);
    }

    #[Route('/admin/contacts/{id}', name: 'contact-detail')]
    public function contact($id): Response
    {
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
