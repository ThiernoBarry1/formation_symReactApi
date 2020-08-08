<?php
namespace App\Doctrine;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CurrentUserExtension  implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{

    private $security;
    private $checker;
    public function __construct(Security $security, AuthorizationCheckerInterface $checker)
    {
        $this->security = $security;
        $this->checker = $checker;
    }
     public function addWhere(QueryBuilder $queryBuilder, $resourceClass)
     {

         $user  = $this->security->getUser();
      
         if(( $resourceClass === "App\Entity\Invoice" || $resourceClass === "App\Entity\Curstomer" ) && (!$this->checker->isGranted("ROLE_ADMIN") )){
            $rootAlias = $queryBuilder->getRootAliases()[0];
            if( $resourceClass === "App\Entity\Invoice"){
                $queryBuilder->join("$rootAlias.customer","c")
                             ->andWhere("c.user = :user");
            }else{
               $queryBuilder->andWhere("$rootAlias.user = :user");
            }
               $queryBuilder->setParameter("user",$user);
                            
         }
     }
    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, 
                     string $resourceClass, ?string $operationName = null)
    {
        $this->addWhere($queryBuilder,$resourceClass);
    }
    
    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator,
                string $resourceClass, array $identifiers, ?string $operationName = null, array $context = [])
    {
        
        $this->addWhere($queryBuilder,$resourceClass);
    }
}
