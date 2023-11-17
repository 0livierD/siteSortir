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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateHeureDebut',DateType::class, [
                'html5'=>true,
                'widget'=>'single_text',
                'attr'=>['class'=>'col-2']
            ])
            ->add('duree')
            ->add('dateLimiteInscription',DateType::class, [
                'label'=>"Limite d'inscription",
                'html5'=>true,
                'widget'=>'single_text'
            ])
            ->add('nbInscriptionMax')
            ->add('infosSortie', TextareaType::class,[
                'label'=>"Informations"
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
                'label' => 'Lieu',
                'attr' => ['id' => 'select_lieu'],
                'placeholder' => 'Choisir un lieu', // Ajoutez cette ligne si vous souhaitez un libellé par défaut
                'required' => false,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
            
        ]);
    }
}
