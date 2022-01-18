<?php

class TesteVenda extends TPage
{
    public function __construct($param)
    {
        parent::__construct();
        
        $woocommerce =  WooConect::getClient();
        $dados = $woocommerce->get('orders', array('per_page'=>1,'page'=>1,'order'=>'asc'));
   
$location = 'https://sandbox.adiantibuilder.com.br/vilela/fashion_biju/rest.php';
error_reporting(0);
ini_set("display_errors", 1);

$parameters = [
    'class' => 'VendaService',
    'method' => 'store',
    'data' => [
            //"id"=> 3923,
            "parent_id"=> 0,
            "status"=> "completed",
            "order_key"=> "wc_order_cuDEKIt2lFFsP",
            "number"=> "3923",
            "currency"=> "BRL",
            "version"=> "4.8.0",
            "prices_include_tax"=> false,
            "date_created"=> "2021-02-10T16=>31=>48",
            "date_modified"=> "2021-02-10T16=>31=>48",
            "customer_id"=> 0,
            "discount_total"=> "0.00",
            "discount_tax"=> "0.00",
            "shipping_total"=> "0.00",
            "shipping_tax"=> "0.00",
            "cart_tax"=> "0.00",
            "total"=> "181.93",
            "total_tax"=> "0.00"
]
];
$rest_key = '1332a3be38efc622d2b7529d9f44a1fbae8236cc9f1f0f865af71c08155a';
$data = AdiantiHttpClient::request($location, 'POST', $parameters, 'Basic '.$rest_key);

       echo'<pre';
       print_r(json_encode($data));
       echo'<pre>';
        


    }
    
    // função executa ao clicar no item de menu
    public function onShow($param = null)
    {
        
    }
}
