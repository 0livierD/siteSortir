<?php

namespace App\Form;

use App\Entity\Filtre;
use App\Entity\Site;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltreType extends AbstractType
{



    public function buildForm(FormBuilderInterface $builder, array $options): void
    {


        $builder
            ->add('site', EntityType::class, [
                'class'=> Site::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder{
                return $er ->createQueryBuilder('s')
                        ->orderBy('s.nom','ASC');
                },
                'label' => 'Site ',
                'required'=>false
            ])
            ->add('nom', TextType::class, ['label'=>'Recherche par nom ',
                'required' => false,
                ] )
            ->add('dateDebut',DateType::class,['label'=>'Début ',
                'required' => false,
                'html5' => true,
                'widget' => 'single_text'])
            ->add('dateFin',DateType::class,['label'=>'Fin ',
                'required' => false,
                'html5' => true,
                'widget' => 'single_text'])
            ->add('sortieQueJOrganise', CheckboxType::class, [
                'label' => 'Sortie que j\'organise',
                'required' => false
            ])
            ->add('sortieOuJeParcitipe', CheckboxType::class, [
                'label' => 'Sortie où je participe',
                'required' => false
            ])
            ->add('sortieOuJeNeParticipePas', CheckboxType::class, [
                'label' => 'Sortie où je ne participe pas',
                'required' => false
            ])
            ->add('sortiePassee', CheckboxType::class, [
                'label' => 'Sortie passée',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Filtre::class
        ]);
    }








}