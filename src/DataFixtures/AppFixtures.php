<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Contact;
use App\Entity\RequestContact;
use App\Repository\ContactRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $contactRepository;
    private $passwordHasher;

    public function __construct(ContactRepository $contactRepository, UserPasswordHasherInterface $passwordHasher )
    {
        $this->contactRepository = $contactRepository;
        $this->passwordHasher = $passwordHasher;

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
            $j = 0;
            while ($j < 3) {
                $requestContact = new RequestContact();

                $requestContact->setContentText($faker->paragraph());
                $requestContact->setIsValidated(false); 
                $requestContact->setContact($this->contactRepository->find($i));                  
                $manager->persist($requestContact);
                $manager->flush();
                $j++;
            }
        }

        $user = new User();
        $emailRegister = 'laurent.lesage51@gmail.com';
        $plaintextPassword = 'artmajeur';
        $role[] = 'ROLE_ADMIN';

        $passwordHashed =  $this->passwordHasher->hashPassword($user, $plaintextPassword);
        $user->setPassword($passwordHashed);
        $user->setEmail($emailRegister);
        $user->setRoles($role);
        $manager->persist($user);
        $manager->flush();



    }
}
