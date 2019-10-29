<?php

namespace App\Tests;

use App\Tests\WebTestCase;

class FilterTest extends WebTestCase
{   
    
    public function testGuestCanFilterProductsByTag()
    {   
        $client = $this->loginAs('guest');

        $crawler = $client->request('GET', '/games');
        
        $tag = $crawler->filter('.tags a')->first()->text();
        $client->request('GET', "/games?search={$tag}");

        $this->assertSelectorTextContains('.product-tags', $tag);
    }

     
    public function testGuestCanFilterProductsByPrice()
    {   
        $client = $this->loginAs('guest');
        $maxPrice = 20;

        $crawler = $client->request('GET', "/games?price={$maxPrice}");
        $this->assertLessThan($maxPrice, $crawler->filter('.price')->text());
    }
    
}
