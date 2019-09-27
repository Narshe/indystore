<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use App\Entity\Product;

class ProductTest extends WebTestCase
{
    public function testGuestCanSeeProducts()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/games');

        $this->assertCount(
            20,
            $crawler->filter('.products div.product')
        );
    }

    public function testAdminCanSeeOneProduct()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'hadrien.giraudeau@gmail.com',
            'PHP_AUTH_PW' => '123456'
        ]);

        $client->request('GET', '/admin/games/10');
        
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
}
