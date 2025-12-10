<?php
namespace App\EventSubscriber;

use App\Event\ConfirmEvent;
use phpDocumentor\Reflection\DocBlock\Tags\Template;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

 class ConfirmRendezVousSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ConfirmEvent::class => 'onConfirmRendezVous',
        ];
    }

    public function onConfirmRendezVous(ConfirmEvent $event): void
    {
        echo"je suis là";
        $visitorList = $event->getVisitorList();
        $patient = $visitorList->getPatient();
        $mailerInterface = $event->getMailerInterface();
        $mail =(new TemplatedEmail)
            ->from(new Address('MS_cWdUfE@test-p7kx4xw9epvg9yjr.mlsender.net',"Tunaweza-Myschedule"))
            
            ->to($patient->getEmail())
            ->subject('Confirmation de votre rendez-vous')
            //->html('<p>Votre rendez-vous a été confirmé.<br/> Send with symfony mailer.</p>')
            ->htmlTemplate("inscription/myNumberEmailTemplate.html.twig")
            ->context([
               "schedule"=>$visitorList,
            ]);
        $mailerInterface->send($mail);
    }
}