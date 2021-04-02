<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Product $product, Request $request)
    {
        if (isset($request->title) || isset($request->variant) || isset($request->price_from) || isset($request->price_to) || isset($request->date)) {
            $title = $request->title ?? '';
            $variant = $request->variant ?? '';
            $price_from = $request->price_from ?? '';
            $price_to = $request->price_to ?? '';
            $date = $request->date ?? '';

            $products = $product->filter($title,$variant,$price_from,$price_to,$date);
            $request->flash();
        }else{
            $products = $product->getAllProducts(2);
        }
        // dd($products);
        $totalProductCount = $product->count();

        $variants = ProductVariant::orderBy('variant_id')->distinct()->with('variantParent')->get();
        return view('products.index', compact('products', 'variants' , 'totalProductCount'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if (isset($request->id) && !empty($request->id)) {
            $product = Product::find($request->id);
        }else{
            $product = new Product;
        }
        $product->title = $request->title;
        $product->sku = $request->sku;
        $product->description = $request->description;
        $product->save();


        foreach($request->product_variant as $v){
            $variant_id = $v['option'];
            foreach ($v['tags'] as $t) {

                $pv = new ProductVariant;

                $pv->variant = $t;
                $pv->variant_id = $variant_id;
                $pv->product_id = $product->id;
                $pv->save();

            }
        }
        foreach($request->product_variant_prices as $vp){

            $productVariantPrice = new ProductVariantPrice;

            $titles = explode('/',$vp['title']);
            if (isset($titles[0]) && $titles[0] != "") {
                $prVrOne =  ProductVariant::where('product_id', $product->id)->where('variant',$titles[0])->first();
                if ($prVrOne) {
                    $productVariantPrice->product_variant_one = $prVrOne->id;
                }
            }
            if (isset($titles[1]) && $titles[1] != "") {
                $prVrTwo =  ProductVariant::where('product_id', $product->id)->where('variant',$titles[1])->first();
                if ($prVrTwo) {
                    $productVariantPrice->product_variant_two = $prVrTwo->id;
                }
            }
            if (isset($titles[2]) && $titles[2] != "") {
                $prVrThree =  ProductVariant::where('product_id', $product->id)->where('variant',$titles[2])->first();
                if ($prVrThree) {
                    $productVariantPrice->product_variant_three = $prVrThree->id;
                }
            }
            $productVariantPrice->product_id  = $product->id;
            $productVariantPrice->price = $vp['price'];
            $productVariantPrice->stock = $vp['stock'];
            $productVariantPrice->save();

            return response()->json($product, 200);
        }

    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $productDetails = $product->load('variants', 'variantPrices');
        $variants = Variant::all();
        return view('products.edit', compact('variants', 'productDetails'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
