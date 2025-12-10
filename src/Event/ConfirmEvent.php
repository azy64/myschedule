<?php
namespace App\Event;
use App\Entity\Patient;
use App\Entity\VisitorList;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\EventDispatcher\Event;

class ConfirmEvent extends Event
{
    private VisitorList $visitorList;
    private MailerInterface $mailerInterface;

    public function __construct(VisitorList $visitorList, MailerInterface $mailerInterface)
    {
        $this->visitorList = $visitorList;
        $this->mailerInterface = $mailerInterface;
    }

    public function getVisitorList()
    {
        return $this->visitorList;
    }
    public function getMailerInterface(): MailerInterface
    {
        return $this->mailerInterface;
    }
}