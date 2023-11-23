<?php

namespace App\Form;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use phpDocumentor\Reflection\Types\True_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use function Sodium\add;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class)
            ->add('dateHeureDebut',DateTimeType::class, [
                'label'=>'Début',
                'html5'=>true,
                'widget'=>'single_text',

            ])
            ->add('duree')
            ->add('dateLimiteInscription',DateTimeType::class, [
                'label'=>"Fin d'inscription",
                'html5'=>true,
                'widget'=>'single_text'
            ])
            ->add('nbInscriptionMax')
            ->add('infosSortie', TextareaType::class,[
                'label'=>"Description",
            ])
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'choice_label' => 'nom',
                'choice_attr' => function (?Lieu $lieu) {
                    return $lieu ? ['data-id' => $lieu->getId()] : [];
                },
                'choice_value' => function (?Lieu $lieu) {
                    return $lieu ? $lieu->getId() : '';
                },
                'placeholder' => 'Choisir un lieu',
                'required' => true,
            ])
            ->add('isPublished' , CheckboxType::class, [
                'label'=>' Publier la sortie?',
                'required' => false, // Si la case n'est pas cochée, la valeur sera null
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,

        ]);
    }
}
