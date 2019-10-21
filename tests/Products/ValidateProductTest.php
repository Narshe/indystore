<?php

namespace App\Tests\Products;

use App\Tests\WebTestCase;


class ValidateProductTest extends WebTestCase
{
    
   /**
     * @dataProvider validateProductProvider
     * @param  $name
     * @param $price
     * @param $visible
     * @param $errorMsg
     */
    public function testValidateProduct($name, $price, $visible, $errorMsg)
    {
        $client = $this->loginAs('admin');
       
        $client->request('GET', '/admin/games/new');
        $client->submitForm('Ajouter le produit', [
            'product[name]' => $name,
            'product[price]' => $price,
            'product[visible]' => $visible,
        ]);
            
        $this->assertSelectorTextContains('li', $errorMsg);
    }

     /**
     * @dataProvider validateProductDetailProvider
     * @param $developer
     * @param $publisher
     * @param $stock
     * @param $soldNumber
     * @param $releaseDateYear
     * @param $errorMsg
     */
    public function testValidateProductDetail($developer, $publisher, $stock, $soldNumber, $releaseDateYear, $errorMsg)
    {
        $client = $this->loginAs('admin');
       
        $client->request('GET', '/admin/games/new');
        $client->submitForm('Ajouter le produit', [
            'product[name]' => "ValidName",
            'product[price]' => 20,
            'product[visible]' => true,
            'product[product_detail][developer]' => $developer,
            'product[product_detail][publisher]' => $publisher,
            'product[product_detail][stock]' => $stock,
            'product[product_detail][soldNumber]' => $soldNumber,
            /** TODO REFACTOR */
            'product[product_detail][releaseDate][month]' => 3,
            'product[product_detail][releaseDate][day]' => 9,
            'product[product_detail][releaseDate][year]' => $releaseDateYear
        ]);
            
        $this->assertSelectorTextContains('li', $errorMsg);
    }

    /**
     * @return Array
     */
    public function validateProductProvider(): Array
    {
        return [
            ["", 5.3,true,"Vous devez remplir le nom du produit"],
            ["name", null,true, "Vous devez renseigner le prix du produit"],
            ["name", "test",true, "Le prix doit être de type float"],
        ];
    }

    public function validateProductDetailProvider(): Array
    {
        return [
            ["", "test", 10 , 0, 2019, "Ce champ ne peut pas être vide"],
            ["test", "", 10 , 0, 2019, "Ce champ ne peut pas être vide"],
            ["test", "test", -1, 0, 2019, "Le nombre doit être supérieur ou égal à zero"],
            ["test", "test", 0, -1, 2019, "Le nombre doit être supérieur ou égal à zero"],
            ["test", "test", null , 0, 2019, "Vous devez entrer un nombre"],
        ];
    }

}
