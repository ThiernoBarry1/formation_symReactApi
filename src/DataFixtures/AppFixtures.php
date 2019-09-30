<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Invoice;
use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{   

 /**
 * encoder de mots de passe
 *
 * @var UserPasswordEncoderInterface
 */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder; 
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for($u = 0; $u < 10 ; $u++){
           $user = new User();
           $user->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setPassword($this->encoder->encodePassword($user,'password'));
            $manager->persist($user);
            for($i=0; $i<mt_rand(5,20) ; $i++){
                $customer = new Customer();
                $customer->setFirstName($faker->firstName)
                         ->setLastName($faker->lastName)
                         ->setEmail($faker->email)
                         ->setCompagny($faker->company)
                         ->setUser($user);
                $manager->persist($customer);
                $chrono = 1;
                for($j = 0;$j< mt_rand(3,10); $j++){
                   $invoice = new Invoice();
                   $invoice->setAmount($faker->randomFloat(2,250,5000))
                           ->setSentAt($faker->dateTimeBetween('-6 months'))
                           ->setStatus($faker->randomElement(['SENT','PAID','CANCELLED']))
                           ->setCustomer($customer)
                           ->setChrono($chrono);
                   $manager->persist($invoice);
                   $chrono++;
                }
            }
            $manager->flush();
        }
    }
}
