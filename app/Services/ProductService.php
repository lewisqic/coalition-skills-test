<?php

namespace App\Services;


class ProductService extends BaseService
{

    /**
     * class constructor
     */
    public function __construct()
    {

    }

    /**
     * create a new product record
     * @param  array  $data [description]
     * @return array
     */
    public function create($data)
    {

        // get existing products
        $products = json_decode(file_get_contents(app_path('products.json')), true);

        // create our new product
        $new_product = [
            'name' => $data['name'],
            'quantity' => $data['quantity'],
            'price' => $data['price'],
            'created_at' => time()
        ];
        $products[] = $new_product;

        // save products file
        file_put_contents(app_path('products.json'), json_encode($products));

        return $new_product;

    }

    /**
     * list our products
     * @param  array  $data [description]
     * @return array
     */
    public function listAll()
    {

        // get existing products
        $products = json_decode(file_get_contents(app_path('products.json')), true);

        // sort by creation date desc
        usort($products, function($a, $b) {
            return $b['created_at'] - $a['created_at'];
        });


        $products_list = [
            'grand_total' => 0,
            'products' => []
        ];
        foreach ( $products as $product ) {
            $products_list['grand_total'] += $product['quantity'] * $product['price'];
            $products_list['products'][] = [
                'name' => $product['name'],
                'quantity' => $product['quantity'],
                'price' => number_format($product['price'], 2),
                'total' => number_format($product['quantity'] * $product['price'], 2),
                'created_at_formatted' => date('Y-m-d H:i:s', $product['created_at']),
            ];
        }
        $products_list['grand_total'] = number_format($products_list['grand_total'], 2);

        return $products_list;



    }

    /**
     * create products file
     * @return array
     */
    public function initProducts()
    {

        // create product json file if it doesn't exist
        if ( !file_exists(app_path('products.json')) ) {
            file_put_contents(app_path('products.json'), '[]');
        }

    }




}
