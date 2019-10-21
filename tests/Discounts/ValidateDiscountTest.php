<?php

namespace App\Tests\Discounts;

use App\Tests\WebTestCase;


class ValidateDiscountTest extends WebTestCase
{
    

     /**
     * @dataProvider validateDiscountProvider
     * @param $title
     * @param $amount
     * @param $begin_at
     * @param $end_at
     * @param $errorMsg
     */
    public function testValidateDiscount($title,$amount,$begin_at,$end_at,$errorMsg)
    {
        $client = $this->loginAs('admin');
       
        $client->request('GET', '/admin/discounts/new');
        $crawler = $client->submitForm('Ajouter', [
            'discount[title]' => $title,
            'discount[amount]' => $amount,
            'discount[begin_at][year]' => $begin_at,
            'discount[end_at][year]' => $end_at,
        ]);
        
        $this->assertContains($errorMsg, $crawler->text());
    }

    /**
     * @return Array
     */
    public function validateDiscountProvider(): Array
    {
        return [
            ["", 50 ,2019,2020, "Vous devez remplir le titre"],
            ["name", null ,2019,2020, "Vous devez entrer un montant"],
            ["name", -5,2019, 2020, "Le montant doit être supérieur à zero"],
            ["name", -5,2020, 2019, "La date de fin doit être supérieur à la date de début"],
        ];
    }

}
