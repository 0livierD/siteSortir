<?php

namespace App\Controller\Admin;

use App\Entity\Site;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nom'),
            TextField::new('prenom'),
            EmailField::new('email'),
            TextField::new('pseudo'),
            TextField::new('telephone'),
            TextField::new('password'),
            TextField::new('photo'),
            AssociationField::new('site')
            ->setFormTypeOption('class', Site::class)
                ->setFormTypeOption('choice_label', 'nom'),
            BooleanField::new('isAdministrateur'),
            BooleanField::new('isActif'),
        ];
    }

}
