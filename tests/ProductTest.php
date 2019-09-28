<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use App\Entity\Product;

class ProductTest extends WebTestCase
{
    public function testGuestCanSeeAllProducts()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/games');

        $this->assertCount(
            20,
            $crawler->filter('.products div.product')
        );
    }

    public function testGuestCanSeeAllProductsWithXhrRequest()
    {   
        $client = static::createClient();
        $client->xmlHttpRequest('GET', '/games');

        $this->assertEquals(
            20,
            count(json_decode($client->getResponse()->getContent(), true)["games"])
        );
    }
    
    public function testGuestCanSeeOneProductsWithXhrRequest()
    {   
        $client = static::createClient();
        $client->xmlHttpRequest('GET', '/games/10');

        $this->assertEquals(
            1,
            count(json_decode($client->getResponse()->getContent(), true))
        );
    }

    public function testGuestCanSeeOneProduct()
    {
        $client = static::createClient();

        $client->request('GET', '/games/10');
        
        $this->assertPageTitleSame('Produit');
        $this->assertResponseIsSuccessful();
    }   

    public function testAdminCanCreateProduct()
    {
        /* TODO Refactor */
        $product = new Product();
        $product->setName("Nom de test");
        $product->setDescription("Description");
        $product->setPrice(200);
        $product->setStock(10);

        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'hadrien.giraudeau@gmail.com',
            'PHP_AUTH_PW' => '123456'
        ]);

        $client->request('GET', '/admin/games/new');

        $client->submitForm('Ajouter le produit', [
            'product[name]' => $product->getName(),
            'product[description]' => $product->getDescription(),
            'product[price]' => $product->getPrice(),
            'product[stock]' => $product->getStock(),
            'product[detail_id]' => $product->getDetailId(),
            'product[visible]' => $product->getVisible(),
        ]);

        $client->followRedirect();

        $this->assertPageTitleSame('Produits');
        $this->assertContains($product->getName(), $client->getResponse()->getContent());
    }

    public function testAdminCanUpdateProduct()
    {   
        $newName = 'Produit modifié';

        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'hadrien.giraudeau@gmail.com',
            'PHP_AUTH_PW' => '123456'
        ]);

        $client->request('GET', '/admin/games');

        $client->clickLink('Editer');

        $this->assertPageTitleSame('Editer un produit');

        $client->submitForm('Modifier le produit', [
            'product[name]' => $newName,
        ]);

        $client->followRedirect();

        $this->assertPageTitleSame('Produits');
        $this->assertContains($newName, $client->getResponse()->getContent());
    }

    public function testAdminCanDeleteProduct()
    {

        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'hadrien.giraudeau@gmail.com',
            'PHP_AUTH_PW' => '123456'
        ]);

        $client->request('GET', '/admin/games');
        
        $client->submitForm('Supprimer');

        $crawler = $client->followRedirect();

        $this->assertCount(
            19,
            $crawler->filter('.products div.product')
        );
    }


    /** TODO REFACTOR VALIDATION */
    /**
     * @dataProvider validateProductProvider
     * @param  $name
     * @param $price
     * @param $stock
     * @param $visible
     * @param $errorMsg
     */
    public function testValidateProduct($name, $price, $stock, $visible, $errorMsg)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'hadrien.giraudeau@gmail.com',
            'PHP_AUTH_PW' => '123456'
        ]);

        $client->request('GET', '/admin/games/new');

        $client->submitForm('Ajouter le produit', [
            'product[name]' => $name,
            'product[price]' => $price,
            'product[stock]' => $stock,
            'product[visible]' => $visible,
        ]);

        $this->assertSelectorTextContains('li', $errorMsg);
    }

    /**
     * @return Array
     */
    public function validateProductProvider(): Array
    {
        return [
            ["", 5.3,5,true, "Le nom le peut pas être vide"],
            ["name", null,5,true, "ous devez entrer un prix"],
            ["name", "test",5,true, "This value is not valid."],
            ["name", 5.3,null,true, "Vous devez entrer une quantité"],
            ["name", 5.3,"test",true, "This value is not valid."],
        ];
    }
}
