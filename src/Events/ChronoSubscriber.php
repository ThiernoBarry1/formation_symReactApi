<?php
namespace App\Events;

use App\Entity\Invoice;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Repository\InvoiceRepository;
use DateTime;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

class ChronoSubscriber  implements EventSubscriberInterface
{   private $security;
    private $repository;

    public function __construct(Security $security,InvoiceRepository $repository)
    {
        $this->security = $security;
        $this->repository = $repository;
    }

    public static function getSubscribedEvents(){
        return [
             KernelEvents::VIEW=>['setChronoForInvoice',EventPriorities::PRE_VALIDATE]
        ];
    }
    public function setChronoForInvoice(GetResponseForControllerResultEvent $event)
    {
       $result = $event->getControllerResult();
       $method = $event->getRequest()->getMethod();
       if($result instanceof Invoice && $method === "POST"){
           // J'ai besoin de trouver l'user actuellement connecté
           $user = $this->security->getUser();
           
           $chrono = $this->repository->findByNextChrono($user);

           $result->setChrono($chrono);
          if(null == $result->getSentAt()){
            $result->setSentAt(new DateTime());
          }
       }
    }
}
