<?php

namespace App\Service;

class ExportContactJson 
{
    public function export($contact, $pathRegister, $email)
    {
        $data = $contact->getContentObject();
        $jsonData = json_encode($data);
        $filename = $pathRegister .'/'.$email."-id-".uniqid().".json";
        file_put_contents($filename, $jsonData);
    }
}
