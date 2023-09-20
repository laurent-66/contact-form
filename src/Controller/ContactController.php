<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Entity\RequestContact;
use App\Service\ExportContactJson;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RequestContactRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{

    private $entityManager;
    private $contactRepository;
    private $requestContactRepository;
    private $exportContactJson;

    public function __construct(
        EntityManagerInterface $entityManager,
        ContactRepository $contactRepository,
        RequestContactRepository $requestContactRepository,
        ExportContactJson $exportContactJson,

    ) {

        $this->entityManager = $entityManager;
        $this->contactRepository = $contactRepository;
        $this->requestContactRepository = $requestContactRepository;
        $this->exportContactJson = $exportContactJson;
    } 

    #[Route('/', name: 'contact-form')]
    public function createContactForm(Request $request ): Response
    {
        $requestcontact = new RequestContact();
        
        $form = $this->createForm(ContactFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $email = $data->getEmail();

            $contact = $this->contactRepository->findOneByEmail($email);

            if($contact){

                $requestcontact->setContentText($data->getComment());
                $this->entityManager->persist($requestcontact);
                $this->entityManager->flush();

                $contact->addRequestContact($requestcontact);

                $this->entityManager->persist($contact);
                $this->entityManager->flush();
                
                $pathRegister = $this->getParameter('contact_json__directory');
                $this->exportContactJson->export($contact, $pathRegister ,$data->getEmail());

            }else {

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

                $pathRegister = $this->getParameter('contact_json__directory');
                $this->exportContactJson->export($contact, $pathRegister ,$data->getEmail());

            }

            $this->addFlash('success', 'Votre demande a été envoyée');

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
    public function contact($id): Response
    {
        $contact = $this->contactRepository->find($id);
        $requestAll = $this->requestContactRepository->getRequestAll($id);

        if($requestAll){

            $requestCompleted = count($this->requestContactRepository->getRequestCompleted($id));
            $requestToMake = count($this->requestContactRepository->getRequestToMake($id));
    
            return $this->render('contact/index.html.twig', [
                'contact' => $contact,
                'requestAll' => $requestAll,
                'requestCompleted' => $requestCompleted,
                'requestToMake' => $requestToMake,
            ]);

        } else {
            return $this-> RedirectToRoute('contact-list');
        }

    }
}
