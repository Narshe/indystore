<?php

namespace App\Tests\Products;

use App\Tests\WebTestCase;

class ValidateProductTest extends WebTestCase
{
    
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
        $client = $this->loginAs('admin');

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
            ["", 5.3,5,true, "Vous devez remplir le nom du produit"],
            ["name", null,5,true, "Vous devez renseigner le prix du produit"],
            ["name", "test",5,true, "Le prix doit être de type float"],
            ["name", 5.3,null,true, "Vous devez renseigner la quatité du produit"],
            ["name", 5.3,"test",true, "La quantité doit être de type integer"],
        ];
    }

}
