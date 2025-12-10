<?php
namespace App\EventSubscriber;

use App\Event\ConfirmEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mime\Address;

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
        //echo"je suis là";
        $visitorList = $event->getVisitorList();
        $patient = $visitorList->getPatient();
        $mailerInterface = $event->getMailerInterface();
        $mail =(new TemplatedEmail)
            ->from(new Address('MS_cWdUfE@test-p7kx4xw9epvg9yjr.mlsender.net',"Tunaweza-Myschedule"))
            ->to(new Address($patient->getEmail(),$patient->getNom().' '.$patient->getPrenom()))
            ->subject('Confirmation de votre rendez-vous')
            ->htmlTemplate("inscription/myNumberEmailTemplate.html.twig")
            ->context([
               "schedule"=>$visitorList,
            ]);
            try{
                $mailerInterface->send($mail);
            }
            catch(\Exception $e){
                echo "Erreur d'envoi de mail: ".$e->getMessage();
            }
    }
}