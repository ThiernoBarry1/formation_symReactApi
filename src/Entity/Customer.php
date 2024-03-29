<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomerRepository")
 * @ApiResource(
 * collectionOperations={"GET","POST"},
 * itemOperations={"GET","PUT","DELETE"},
 * normalizationContext={
 *  "groups"={"customers_read"}
 * }
 * )
 * @ApiFilter(
 * SearchFilter::class,properties={"firstName":"partial","lastName","compagny"}
 * )
 */
class Customer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"customers_read","invoices_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"customers_read","invoices_read"})
     * @Assert\NotBlank(message="Le prénom ne doit pas être vide")
     * @Assert\Length( min = 2,
     *                 minMessage="Le prénom doit être supperieur à {{ limit }}",
     *                 max = 10,
     *                 maxMessage="Le prénom doit être inferieur à {{ limit }}"
     *                )
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups({"customers_read","invoices_read"})
     * @Assert\NotBlank(message="Le prénom ne doit pas être vide")
     * @Assert\Length( min = 2,
     *                 minMessage="Le nom être supperieur à {{ limit }}",
     *                 max = 10,
     *                 maxMessage="Le nom doit être inferieur à {{ limit }}"
     *                )
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *  @Groups({"customers_read","invoices_read"})
     * @Assert\NotBlank(message="Le prénom ne doit pas être vide")
     * @Assert\Email(message="Vous devez donner une adresse mail valide")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *  @Groups({"customers_read","invoices_read"})
     */
    private $compagny;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Invoice", mappedBy="customer")
     *  @Groups({"customers_read"})
     * @ApiSubresource
     */
    private $invoices;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="customers")
     *  @Groups({"customers_read"})
     * @Assert\NotBlank(message="Un user est obligatoire")
     */
    private $user;

    public function __construct()
    {
        $this->invoices = new ArrayCollection();
    }   
    /**
     * permet de calculer le total des invoices.
     * @Groups({"customers_read"})
     *
     * @return float
     */
    public function getMontantTotal():float
    {
        return array_reduce($this->invoices->toArray(),function($total,$invoices){
            return $total + $invoices->getAmount();
        },0.0);
    } 
    /**
     * permet de calculer le montant du client dû.
     * 
     *@Groups({"customers_read"})

     * @return float
     */
    public function getAmountNotPaye():float
    {
        return array_reduce($this->invoices->toArray(), function($total,$invoices) {
            
            if ( $invoices->getStatus() === "SENT"){
              return $total +  $invoices->getAmount();
            }
            return $total;
            
            //return $invoices->getStatus() === "PAID" || $invoices->getStatus() === "CANCELLED" ? $total +  0.0:$total + $invoices->getAmount();
        },0.0);
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCompagny(): ?string
    {
        return $this->compagny;
    }

    public function setCompagny(?string $compagny): self
    {
        $this->compagny = $compagny;

        return $this;
    }

    /**
     * @return Collection|Invoice[]
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function addInvoice(Invoice $invoice): self
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices[] = $invoice;
            $invoice->setCustomer($this);
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): self
    {
        if ($this->invoices->contains($invoice)) {
            $this->invoices->removeElement($invoice);
            // set the owning side to null (unless already changed)
            if ($invoice->getCustomer() === $this) {
                $invoice->setCustomer(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
