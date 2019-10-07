<?php

namespace App\Tests;

use App\Tests\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;
use Doctrine\Common\Collections\ArrayCollection;

use App\Entity\Cart;

class CartTest extends WebTestCase
{
    public function testGuestCanAddProductToCart()
    {
        $client = $this->loginAs('guest');

        $client->request('GET', '/games');

        $client->submitForm('Ajouter au panier');

        $crawler = $client->followRedirect();
        $this->assertCount(
            1,
            $crawler->filter('.products div.product')
        );
    }

    public function testGuestCanIncreaseProductQuantity()
    {
        $client = $this->loginAs('guest');

        $client->request('GET', '/games');

        $client->submitForm('Ajouter au panier');

        $crawler = $client->followRedirect();

        $client->submitForm('+');

        $crawler = $client->followRedirect();

        $this->assertSelectorTextContains('.products .product p.quantity', '2');
    }

    /**
     * @dataProvider removeCartProvider
     * @param string $button
     * @param int $quantity
     */
    public function testGuestCanRemoveProductFromCart(string $button, int $quantity)
    {   
       
        $client = $this->loginAs('guest');

        $client->request('GET', '/games');
         
        $client->submitForm('Ajouter au panier');

        $client->followRedirect();

        $client->submitForm($button);

        $crawler = $client->followRedirect();

        $this->assertCount(
            $quantity,
            $crawler->filter('.products div.product')
        );
        
    }

    /**
     * @return Array
     */
    public function removeCartProvider(): Array
    {
        return [
            ['-', 0],
            ['Vider le panier', 0]
        ];

    }

    /*
      TODO REFACTOR TEST SESSION

     */
  
}

