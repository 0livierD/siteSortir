<?php

namespace App\services;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\User\UserInterface;

class Sender
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer=$mailer;
    }

    public function sendNewUserNotificationToAdmin(UserInterface $user)
    {
        $email = new Email();
        $email->from('admin@sortir.fr')
            ->to('admin@sortir.fr')
            ->subject('Nouveau compte créé')
            ->html('<h1>Nouveau compte créé</h1>Utilisateur : ' . $user->getUserIdentifier());
        $this->mailer->send($email);
    }
}