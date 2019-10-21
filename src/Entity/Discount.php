<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DiscountRepository")
 * @UniqueEntity(fields={"title"}, message="Cette promotion existe déjà")
 */
class Discount
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez remplir le titre")
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Vous devez entrer un montant")
     * @Assert\Positive(message="Le montant doit être supérieur à zero")
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThanOrEqual(
     *  value="today",
     *  message="La date de début doit être supérieur ou égal à la date du jour"
     * )
     */
    private $begin_at;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThan(
     *  propertyPath="begin_at",
     *  message="La date de fin doit être supérieur à la date de début"
     * )
     */
    private $end_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductDetail", mappedBy="discount")
     */
    private $productDetails;


    public function __construct()
    {
        $this->productDetails = new ArrayCollection();
        $this->begin_at = new \DateTime();
        $this->end_at = new \DateTime('+1 DAY');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getBeginAt(): ?\DateTimeInterface
    {
        return $this->begin_at;
    }

    public function setBeginAt(\DateTimeInterface $begin_at): self
    {
        $this->begin_at = $begin_at;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->end_at;
    }

    public function setEndAt(\DateTimeInterface $end_at): self
    {
        $this->end_at = $end_at;

        return $this;
    }

    /**
     * @return Collection|ProductDetail[]
     */
    public function getProductDetails(): Collection
    {
        return $this->productDetails;
    }

    public function addProductDetail(ProductDetail $productDetail): self
    {
        if (!$this->productDetails->contains($productDetail)) {
            $this->productDetails[] = $productDetail;
            $productDetail->setDiscount($this);
        }

        return $this;
    }

    public function removeProductDetail(ProductDetail $productDetail): self
    {
        if ($this->productDetails->contains($productDetail)) {
            $this->productDetails->removeElement($productDetail);
            // set the owning side to null (unless already changed)
            if ($productDetail->getDiscount() === $this) {
                $productDetail->setDiscount(null);
            }
        }

        return $this;
    }

    /**
     * @return string;
     */
    public function getDiscountTitle(): string
    {

        return "{$this->getTitle()} (-{$this->getAmount()}%)";
    }
}
