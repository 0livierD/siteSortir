<?php

namespace App\EventSubscriber;

use App\Entity\Sortie;
use App\Entity\User;
use App\services\EtatUser;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $entityManager;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['hashPassword'],
            BeforeEntityUpdatedEvent::class => ['hashPassword'],

        ];
    }

    public function hashPassword($event)
    {

        $entity = $event->getEntityInstance();


        if (!($entity instanceof User)) {
            return;
        }

        $this->updatePassword($entity);
    }

    private function updatePassword(User $user)
    {
        if (!$user->getPlainPassword()) {
            if(!$user->isIsActif()){
                $etatUser = new EtatUser();
                $etatUser->annulerSortiesUser($user,$this->entityManager);
                return;
            } else{
                $etatUser = new EtatUser();
                $etatUser->reactiverSortieUser($user,$this->entityManager);
                return;
            }

            return;
        }

        $hashedPassword = $this->passwordEncoder->hashPassword($user, $user->getPlainPassword());
        $user->setPassword($hashedPassword);

        // Clear the plain password to avoid persisting it
        $user->setPlainPassword(null);
    }
}