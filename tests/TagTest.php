<?php

namespace App\Tests;

use App\Tests\WebTestCase;

class TagTest extends WebTestCase
{   
    
    public function testGuestCanFilterProductsByTag()
    {   

        $client = $this->loginAs('guest');

        $client->request('GET', '/games?search=Fantasy, Adventure');

       // $this->assertSelectorTextContains('div.tags', 'Fantasy');
       // $this->assertSelectorTextContains('div.tags', 'Adventure');
    }
    
}
