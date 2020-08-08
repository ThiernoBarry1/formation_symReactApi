<?php
namespace App\Events;

use App\Entity\User;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

class PasswordEncoderSubscriber implements EventSubscriberInterface{

    private $encoder ;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;  
    }
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['encodePassword',EventPriorities::PRE_WRITE]
        ];
    }
    
    public function encodePassword(GetResponseForControllerResultEvent  $event)
    {
        $resulte = $event->getControllerResult();
        $method = $event->getRequest()->getMethod(); // PUT,POST,

        if( $resulte instanceof User && $method === "POST"){
            $hash = $this->encoder->encodePassword($resulte,$resulte->getPassword());
            $resulte->setPassword($hash);
        }
    }

}

