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
            'id' => uniqid(),
            'name' => $data['name'],
            'quantity' => (float)$data['quantity'],
            'price' => (float)$data['price'],
            'created_at' => time()
        ];
        $products[] = $new_product;

        // save products file
        file_put_contents(app_path('products.json'), json_encode($products));

        return $new_product;

    }

    /**
     * delete a product
     * @return array
     */
    public function delete($id)
    {

        // get existing products
        $products = json_decode(file_get_contents(app_path('products.json')), true);

        // delete the product
        foreach ( $products as $key => $product ) {
            if ( $product['id'] == $id ) {
                unset($products[$key]);
                break;
            }
        }

        // save products file
        file_put_contents(app_path('products.json'), json_encode($products));


    }

    /**
     * delete a product
     * @return array
     */
    public function update($id, $data)
    {

        // get existing products
        $products = json_decode(file_get_contents(app_path('products.json')), true);

        // update the product
        foreach ( $products as $key => $product ) {
            if ( $product['id'] == $id ) {
                $products[$key]['name'] = $data['name'];
                $products[$key]['quantity'] = (float)$data['quantity'];
                $products[$key]['price'] = (float)$data['price'];
            }
        }

        // save products file
        file_put_contents(app_path('products.json'), json_encode($products));

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
                'id' => $product['id'],
                'name' => $product['name'],
                'quantity' => $product['quantity'],
                'price' => number_format((float)$product['price'], 2),
                'total' => number_format((float)$product['quantity'] * (float)$product['price'], 2),
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
