<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName',TextType::class, ['label'=>'Prénom', 'required'=> false])
            ->add('lastName',TextType::class,['label'=>'Nom','required'=> false] )
            ->add('email',TextType::class, ['label'=>'email','required'=> false])
            ->add('comment',TextareaType::class, ['label'=>'Question','required'=> false])
            ->add('save', SubmitType::class, ['label'=>'valider'] )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
