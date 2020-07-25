<?php

namespace App\Controller;

use App\Entity\Invoice;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InvoiceIncrementationController extends AbstractController
{
    private $em;
    
    public function __construct(EntityManagerInterface $em)
    {
           $this->em = $em;
    }
    public function __invoke(Invoice $data)
    {
        $data->setChrono($data->getChrono() + 1 );
        
        $this->em->flush();
       return $data;
       
    }
}
