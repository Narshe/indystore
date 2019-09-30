<?php

namespace App\Tests;

use App\Tests\WebTestCase;

class CategoryTest extends WebTestCase
{
    public function testGuestCanSeeCategories()
    {

        $client = $this->loginAs('guest');

        $crawler = $client->request('GET', '/');

        $this->assertCount(
            10,
            $crawler->filter('ul.categories li.category')
        );
    }

    public function testGuestCanSortProductByCategory()
    {   

        $client = $this->loginAs('guest');
        $crawler = $client->request('GET', '/games');

        $categoryName = $crawler
                    ->filter('li.category a')
                    ->first()
                    ->text()
        ;
        $client->clickLink($categoryName);
        
        $this->assertSelectorTextContains('h1', $categoryName);

    }

    public function testAdminCanSeeAllCategories()
    {

        $client = $this->loginAs('admin');

        $crawler = $client->request('GET', '/admin/categories');

        $this->assertCount(
            10,
            $crawler->filter('.categories div.category')
        );

    }

    public function testAdminCanCreateNewCategory()
    {
        $newCategorie = 'Une nouvelle catégorie';

        $client = $this->loginAs('admin');

        $client->request('GET', '/admin/categories/new');

        $client->submitForm('Ajouter la catégorie', [
            'category[name]' => $newCategorie
        ]);

        $crawler = $client->followRedirect();

        $this->assertContains($newCategorie, $client->getResponse()->getContent());
    }

    public function testAdminCanUpdateCategory()
    {
        $newCategorieName = 'Une nouvelle catégorie update';

        $client = $this->loginAs('admin');

        $client->request('GET', '/admin/categories');

        $client->clickLink('Editer');

        $client->submitForm('Modifier la catégorie', [
            'category[name]' => $newCategorieName
        ]);

        $client->followRedirect();

        $this->assertContains($newCategorieName, $client->getResponse()->getContent());
    }


    public function testAdminCanDestroyCategory()
    {

        $client = $this->loginAs('admin');

        $client->request('GET', '/admin/categories');

        $client->submitForm('Supprimer');

        $crawler = $client->followRedirect();

        $this->assertCount(
            9,
            $crawler->filter('.categories div.category')
        );
    }

}
