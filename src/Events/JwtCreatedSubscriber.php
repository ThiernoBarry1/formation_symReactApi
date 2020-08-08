<?php
namespace App\Events;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class JwtCreatedSubscriber  
{
    public function updateJWTData(JWTCreatedEvent $event){
       
        $user = $event->getUser();

        $data = $event->getData();
        $data['firsName'] = $user->getFirstName();
        $data['lastName'] = $user->getLastName();
        $event->setData($data);
    }
}
