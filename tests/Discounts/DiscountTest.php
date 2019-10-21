<?php

namespace App\Tests\Discount;

use App\Tests\WebTestCase;

class DiscountTest extends WebTestCase
{   
    public function testAdminCanSeeAllDiscount()
    {
        $client = $this->loginAs('admin');

        $crawler = $client->request('GET', '/admin/discounts');

        $crawler = $client->followRedirect();

        $this->assertCount(
            4,
            $crawler->filter('table tr')
        );
    }

    public function testAdminCanCreateNewDiscount()
    {
        $newDiscount = 'Une nouvelle promotion';
        $newAmount = 90;

        $client = $this->loginAs('admin');

        $client->request('GET', '/admin/discounts/new');

        $client->submitForm('Ajouter', [
            'discount[title]' => $newDiscount,
            'discount[amount]' => $newAmount,
        ]);

        $client->followRedirect();

        $this->assertContains($newDiscount, $client->getResponse()->getContent());
    }

    public function testAdminCanUpdateDiscount()
    {
        $newDiscountTitle = "Titre modifié";

        $client = $this->loginAs('admin');

        $client->request('GET', '/admin/discounts');

        $client->followRedirect();

        $client->clickLink('Edit');

        $client->submitForm('Modifier', [
            'discount[amount]' => $newDiscountTitle
        ]);
        
        $this->assertContains($newDiscountTitle, $client->getResponse()->getContent());
    }


    public function testAdminCanDestroyDiscount()
    {

        $client = $this->loginAs('admin');

        $client->request('GET', '/admin/discounts');

        $client->followRedirect();
        
        $client->submitForm('Supprimer');

        $crawler = $client->followRedirect();

        $this->assertCount(
            3,
            $crawler->filter('table tr')
        );
    }

}
