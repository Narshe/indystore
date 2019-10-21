<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductDetailRepository")
 */
class ProductDetail
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", options={"default":0})
     * @Assert\PositiveOrZero(message="Le nombre doit être supérieur ou égal à zero")
     * @Assert\NotBlank(
     *  message="Vous devez entrer un nombre"
     * )
     */
    private $stock;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *  message="Ce champ ne peut pas être vide"
     * )
     * 
     */
    private $developer;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *  message="Ce champ ne peut pas être vide"
     * )
     */
    private $publisher;

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero(message="Le nombre doit être supérieur ou égal à zero")
     */
    private $soldNumber;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $releaseDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Discount", inversedBy="productDetails")
     */
    private $discount;
    
    public function __construct()
    {
        $this->releaseDate = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getDeveloper(): ?string
    {
        return $this->developer;
    }

    public function setDeveloper(string $developer): self
    {
        $this->developer = $developer;

        return $this;
    }

    public function getPublisher(): ?string
    {
        return $this->publisher;
    }

    public function setPublisher(string $publisher): self
    {
        $this->publisher = $publisher;

        return $this;
    }

    public function getSoldNumber(): ?int
    {
        return $this->soldNumber;
    }

    public function setSoldNumber(int $soldNumber): self
    {
        $this->soldNumber = $soldNumber;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(?\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getDiscount(): ?Discount
    {
        return $this->discount;
    }

    public function setDiscount(?Discount $discount): self
    {
        $this->discount = $discount;

        return $this;
    }
}
