<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * configuration pour cette activité. Je surcharge la configuration
 * api_plateform  
 * 
 * @ORM\Entity(repositoryClass="App\Repository\InvoiceRepository")
 * @ApiResource(
 * itemOperations={"GET","PUT","DELETE","increment"={
 *      "method"="POST",
 *      "path"="/invoices/{id}/increment",
 *      "controller"=InvoiceIncrementationController::class,
 *      "swagger_context"={ 
 *                           "summary":"Incremente une facture", 
 *                           "description":"Permet d'incremeter un chrono pour une facture donnée"
 *                         }
 *        }
 * }
 * ,
 * normalizationContext={
 *  "groups"={"invoices_read"}
 * },
 *
 * subresourceOperations={
 *  "api_customers_invoices_get_subresource"={
 *   "normalization_context"={"groups"="invoices_subresource"} 
 * }
 * }
 * ,
 * attributes={"pagination_enabled"=true,
 * "pagination_items_per_page" = 20,
 * "order":{"amount":"desc"}
 * }
 * )
 *@ApiFilter(OrderFilter::class)
 * 
 */
class Invoice
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"invoices_read","customers_read","invoices_subresource"})
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     *  @Groups({"invoices_read","customers_read","invoices_subresource"})
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *  @Groups({"invoices_read","customers_read","invoices_subresource"})
     */
    private $sentAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"invoices_read","customers_read","invoices_subresource"})
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="invoices")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"invoices_read"})
     */
    private $customer;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"invoices_read","customers_read","invoices_subresource"})
     */
    private $chrono;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
    /**
     * renvoie un user 
     * 
     * @Groups({"invoices_read"})
     *
     * @return User
     */
    public function getUser():User{
        return $this->customer->getUser();
    }
    public function getSentAt(): ?\DateTimeInterface
    {
        return $this->sentAt;
    }

    public function setSentAt(?\DateTimeInterface $sentAt): self
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getChrono(): ?int
    {
        return $this->chrono;
    }

    public function setChrono(?int $chrono): self
    {
        $this->chrono = $chrono;

        return $this;
    }
}
