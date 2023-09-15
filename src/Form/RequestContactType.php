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

    public function __construct(RequestContactRepository $requestContactRepository)
    {
        $this->requestContactRepository = $requestContactRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // $requestAll = $this->requestContactRepository->getRequestAll(RequestContact::class);

        $builder->add('isValidated', CheckboxType::class, [
            'label' => 'Option ',
            'required' => false, 
        ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RequestContact::class,
        ]);
    }
}
