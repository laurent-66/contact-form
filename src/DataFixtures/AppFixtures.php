<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Contact;
use App\Entity\RequestContact;
use App\Repository\ContactRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 5; $i++) {
            $contact = new Contact();
            $firstName = $faker->firstName();
            $lastName = $faker->lastName();

            $contact->setFirstName($firstName);
            $contact->setLastName($lastName);
            $contact->setEmail($firstName.'@'.$lastName.'.fr'); 
            $contact->setWebmaster(false);  
            $manager->persist($contact);
            $manager->flush();
        }

        for ($i = 1; $i < 6; $i++) {
            $requestContact = new RequestContact();
            $requestContact->setContentText($faker->paragraph());
            $requestContact->setIsValidated(false); 
            $requestContact->setContact($this->contactRepository->find($i));                  
            $manager->persist($requestContact);
            $manager->flush();
        }

    }
}
