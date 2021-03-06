<?php

namespace App\Form;

use App\Entity\Abonne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\IsTrue;

class AbonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class, [
                "label" => "Pseudo*",
                "constraints" =>[
                    new Length([
                        "min" => 4,
                        "minMessage" => "Le pseudo doit comporter au moins 4 caractères",
                        "max" => 10,
                        "maxMessage" => "Le pseudo ne peut pas comporter plus de 10 caractères"
                    ])
                ]
            ])
            
            ->add('password', PasswordType::class, [
                "label" => "Mot de passe*"
            ])
            ->add('roles', ChoiceType::class, [
                "choices" => [
                    "Lecteur" => "ROLE_USER",
                    "Bibliothécaire" => "ROLE_BIBLIOTHECAIRE",
                    "Directeur" => "ROLE_ADMIN"
                ],
                "multiple" => true,
                "expanded" => true
            ])
            ->add('prenom', TextType::class, [
                "label" => "Prénom"
            ])
            ->add('nom', TextType::class, [
                "required" => false
            ])
            ->add('cgu',CheckboxType::class, [
                "label" => "J'accepte les Conditions Générales d'utilisation",
                "mapped" => false,
                "required" => false,
                "constraints" => [
                    new IsTrue([
                        "message" => "Vous devez accepter les C.G.U"
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Abonne::class,
        ]);
    }
}
