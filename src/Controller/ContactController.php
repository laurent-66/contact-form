<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Entity\RequestContact;
use App\Form\RequestContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RequestContactRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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

    #[Route('/', name: 'contact-form')]
    public function createContactForm(Request $request): Response
    {
        $requestcontact = new RequestContact();
        
        $form = $this->createForm(ContactFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $email = $data->getEmail();

            $contact = $this->contactRepository->findOneByEmail($email);

            if($contact){
                echo ' l\'email existe';

                $requestcontact->setContentText($data->getComment());
                $this->entityManager->persist($requestcontact);
                $this->entityManager->flush();

                $contact->addRequestContact($requestcontact);

                $this->entityManager->persist($contact);
                $this->entityManager->flush();


            }else {
                echo ' l\'email n\'existe pas';

                $requestcontact->setContentText($data->getComment());
                $this->entityManager->persist($requestcontact);
                $this->entityManager->flush();

                $contact = new Contact();
                $contact->setFirstName($data->getFirstName());
                $contact->setLastName($data->getLastName());
                $contact->setEmail($data->getEmail());
                $contact->addRequestContact($requestcontact);
                $this->entityManager->persist($contact);
                $this->entityManager->flush();
            }

            $this->addFlash('success', 'votre demande à bien été envoyé');

            return $this->redirectToRoute('contact-form');
        }

        return $this->renderForm('contactForm/index.html.twig', [
            'form' => $form,
        ]);
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
    public function contact(Request $request, $id): Response
    {
        $requestAll = $this->requestContactRepository->getRequestAll($id);

        foreach( $requestAll as $requestContact ){

            $form = $this->createForm(RequestContactType::class, $requestContact);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $requestContact = $form->getData();
            }
            // dump($requestContact);

            // return $this->redirectToRoute('contact-list');
        }

        $contact = $this->contactRepository->find($id);
        $requestCompleted = count($this->requestContactRepository->getRequestCompleted($id));
        $requestToMake = count($this->requestContactRepository->getRequestToMake($id));


        return $this->renderForm('contact/index.html.twig', [
            'contact' => $contact,
            'requestAll' => $requestAll,
            'requestCompleted' => $requestCompleted,
            'requestToMake' => $requestToMake,
            'form' => $form,
        ]);
    }
}
