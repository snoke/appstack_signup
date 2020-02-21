<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SignupFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => false,'attr' => array('placeholder' => 'Benutzername'),
                'constraints' => array(
                    new NotBlank(array("message" => "Bitte Benutzernamen angeben!")),
                )
            ))
			->add('password', RepeatedType::class, [
				'type' => PasswordType::class,
				'invalid_message' => 'Die Felder stimmen nicht überein!',
				'options' => ['attr' => ['class' => 'password-field']],
				'required' => true,
				'first_options'  => ['label' => false,'attr' => array('placeholder' => 'Passwort')],
				'second_options' => ['label' => false,'attr' => array('placeholder' => 'Passwort wiederholen')],
			])
            ->add('submit', SubmitType::class, array('label' => 'registrieren','attr' => array('class'=>"w-100 btn btn-primary")));
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
