<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null,  ['label' => '*Votre Email'])
            ->add('firstname', null,  ['label' => '*Votre Prénom'])
            ->add('lastname', null,  ['label' => '*Votre Nom'])
            ->add('picture', null,  ['label' => '*Votre Photo'])
            ->add('password', PasswordType::class, ['label' => '*Mot de passe']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
