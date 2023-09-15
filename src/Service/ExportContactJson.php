<?php

namespace App\Service;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ExportContactJson 
{
    public static function export($contact, $requestcontact, $pathRegister, $email)
    {
        $datasRequestContact = [
            $requestcontact->getId(),
            $requestcontact->getContentText(),
            $requestcontact->isIsValidated(),
            $requestcontact->getCreatedAt(),
            $requestcontact->getUpdatedAt(),  
        ];

        $datasContact = [
            $contact->getFirstName(),
            $contact->getLastName(),
            $contact->getEmail(),
            $contact->isWebmaster(),
            $contact->getCreatedAt(),
            [...$datasRequestContact ],           
        ];




        dump($datasContact);
        dump($pathRegister);
        dump($email);


        $jsonData = json_encode($datasContact);
        // dd($jsonData);

        // $encoder = new JsonEncoder();
        // $normalizer = new ObjectNormalizer();
        // $normalizer = new ObjectNormalizer(null, null, null, null, null, null, [
        //     ObjectNormalizer::CIRCULAR_REFERENCE_LIMIT => 1, 
        // ]);
        // $serializer = new Serializer([$normalizer], [$encoder]);

        // $jsonData = $serializer->serialize($object,'json',['groups' => 'getContacts']);


        $filename = $pathRegister .'/'.$email.".json";

        // Enregistrez les données JSON dans un fichier
        if (file_put_contents($filename, $jsonData)) {
            echo "Les données ont été enregistrées dans $filename avec succès.";
        } else {
            echo "Une erreur s'est produite lors de l'enregistrement des données.";
        }
    }
}