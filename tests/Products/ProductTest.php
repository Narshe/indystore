<?php

namespace App\Tests\Products;

use App\Tests\WebTestCase;
use App\Entity\Product;
use APp\Entity\User;


class ProductTest extends WebTestCase
{
    public function testGuestCanSeeAllProducts()
    {
        $client = $this->loginAs('guest');

        $crawler = $client->request('GET', '/games');

        $this->assertCount(
            25,
            $crawler->filter('.products div.product')
        );
    }

    public function testGuestCanSeeAllProductsWithXhrRequest()
    {   
        $client = $this->loginAs('guest');
        $client->xmlHttpRequest('GET', '/games');

        $this->assertEquals(
            25,
            count(json_decode($client->getResponse()->getContent(), true)["products"])
        );
    }
    
    public function testGuestCanSeeOneProductsWithXhrRequest()
    {   
        $client = $this->loginAs('guest');
        $client->xmlHttpRequest('GET', '/games/10');
        
        $this->assertNotNull(json_decode($client->getResponse()->getContent(), true));
    }

    public function testGuestCanSeeOneProduct()
    {
        $client = $this->loginAs('guest');

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
        $product->setPrice(200.25);
       // $product->setStock(10);

        $client = $this->loginAs('admin');
        
        $client->request('GET', '/admin/games/new');

        $this->assertResponseIsSuccessful();
        $client->submitForm('Ajouter le produit', [
            'product[name]' => $product->getName(),
            'product[description]' => $product->getDescription(),
            'product[price]' => $product->getPrice(),
            'product[category]' => 1,
            'product[visible]' => $product->getVisible(),
            'product[product_detail][stock]' => 3,
            'product[product_detail][developer]' => "test",
            'product[product_detail][publisher]' => "test",
            'product[product_detail][soldNumber]' => 9,
            'product[product_detail][releaseDate][month]' => 3,
            'product[product_detail][releaseDate][day]' => 9,
            'product[product_detail][releaseDate][year]' => 2019
        ]);

        $client->followRedirect();

        $this->assertPageTitleSame('Produits');
        $this->assertContains($product->getName(), $client->getResponse()->getContent());
    }

    public function testAdminCanUpdateProduct()
    {   
        $newName = 'Produit modifié';

        $client = $this->loginAs('admin');

        $client->request('GET', '/admin/games');

        $client->clickLink('Editer');

        $this->assertPageTitleSame('Editer un produit');

        $client->submitForm('Modifier le produit', [
            'product[name]' => $newName,
            'product[category]' => 2
        ]);

        $client->followRedirect();

        $this->assertPageTitleSame('Produits');
        $this->assertContains($newName, $client->getResponse()->getContent());
    }

    public function testAdminCanDeleteProduct()
    {

        $client = $this->loginAs('admin');

        $client->request('GET', '/admin/games');
        
        $client->submitForm('Supprimer');

        $crawler = $client->followRedirect();

        $this->assertCount(
            49,
            $crawler->filter('.products div.product')
        );
    }
    
}
