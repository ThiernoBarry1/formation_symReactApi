<?php
namespace App\Events;

use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Customer;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class CustomerUserSubscriber  implements EventSubscriberInterface
{   
    private $security;
    public function __construct(Security $security){
       $this->security  = $security;
    }

    public static function getSubscribedEvents(){
       return [
            KernelEvents::VIEW => ['setUserForCustomer',EventPriorities::PRE_VALIDATE]
       ];
    }

    public function setUserForCustomer(GetResponseForControllerResultEvent $event){
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if($result instanceof Customer && $method === 'POST'){

            // recuper l'utilisateur actuellement connecté
                $user = $this->security->getUser();
            // assigné l'utilisateur au customer
             $result->setUser($user);
            
        }
        
    }

}
