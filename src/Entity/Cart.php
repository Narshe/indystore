<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use App\Entity\Product;

class Cart
{   

    /**
     * @var ArrayCollection
     */
    private $cart;


    public function __construct(ArrayCollection $cart)
    {   
        $this->cart = $cart;
    }

    /**
     * @return ArrayCollection
     */
    public function getCart(): ArrayCollection
    {
        return $this->cart;
    }


    /**
     * @param int $id
     * @return self
     */
    public function addProduct(int $id): self
    {
        $this->cart[$id] = $this->cart->containsKey($id) ? $this->cart[$id] + 1 : 1;

        return $this;
    }

    /**
     * @param int $id
     * @return self
     */
    public function removeProduct(int $id): self
    {   

        if ($this->cart->containsKey($id)) {

            if ($this->cart[$id] > 1) {
                $this->cart[$id] = $this->cart[$id] - 1;
            } else {
                unset($this->cart[$id]);
            }
        }

        return $this;
    }

    /**
     * @param array $product
     * @return float
     */
    public function getPriceWithQuantity(Product $product): float
    {
        return $product->getDiscountedPrice() * $this->getProductQuantity($product->getId());
    }

    /**
     * @param Array $products
     * @return float
     */
    public function getTotalPrice(array $products): float
    {
        $total = 0;

        foreach($products as $key => $product) {

            $total += $this->getPriceWithQuantity($product);
        }

        return $total;
    }


    /**
     * @param int $id
     * @return int
     */
    public function getProductQuantity(int $id): int
    {
        return $this->cart[$id];
    }
}