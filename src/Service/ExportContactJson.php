<?php

namespace App\Service;

class ExportContactJson 
{
    public function export($contact, $pathRegister, $email)
    {

        $data = $contact->getContentObject();

        $jsonData = json_encode($data);

        $filename = $pathRegister .'/'.$email."-id-".uniqid().".json";

        // Enregistrez les données JSON dans un fichier
        if (file_put_contents($filename, $jsonData)) {
            echo "Les données ont été enregistrées dans $filename avec succès.";
        } else {
            echo "Une erreur s'est produite lors de l'enregistrement des données.";
        }
    }
}