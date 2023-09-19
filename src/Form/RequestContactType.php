<?php

namespace App\Form;

use App\Entity\RequestContact;
use App\Repository\RequestContactRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RequestContactType extends AbstractType
{
    private $requestContactRepository;
    public $id;

    public function __construct(RequestContactRepository $requestContactRepository, $id)
    {
        $this->requestContactRepository = $requestContactRepository;
        $this->id = $id;
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $requestContacts = $this->requestContactRepository->findByContact($this->id);

        foreach($requestContacts as $question) {

        $builder->add('isValidated', CheckboxType::class, [
                'label' => $question->getContText(),
                'required' => false, 
            ]);
        };

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RequestContact::class,
        ]);
    }
}
