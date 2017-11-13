<?php

namespace App\Http\Controllers\Index;

use App\Services\ProductService;
use App\Http\Controllers\Controller;

class IndexIndexController extends Controller
{

    /**
     * declare our services to be injected
     */
    protected $productService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ProductService $ps)
    {
        $this->productService = $ps;
        $this->productService->initProducts();
    }

    /**
     * Show the products page
     *
     * @return view
     */
    public function showProducts()
    {

        $products = $this->productService->listAll();

        $data = [
            'products_json' => json_encode($products)
        ];

        return view('content.index.index.home', $data);

    }

    /**
     * add a new product
     *
     * @return json
     */
    public function addProduct()
    {

        $data = \Request::all();
        $product = $this->productService->create($data);
        $products = $this->productService->listAll();

        $response = [
            'success' => true,
            'product' => $product,
            'all_products' => $products
        ];
        return response()->json($response);


    }




}
