<?php

namespace App\Http\Controllers;

use App\Models\ecommerce;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class EcommerceController extends Controller
{
    public function Products_Category(Request $request)
    {
        // dd($request->category);
      
        // return view('frontend.product-promotion', $data);
        
    }



    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ecommerce  $ecommerce
     * @return \Illuminate\Http\Response
     */
    public function show(ecommerce $ecommerce)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ecommerce  $ecommerce
     * @return \Illuminate\Http\Response
     */
    public function edit(ecommerce $ecommerce)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ecommerce  $ecommerce
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ecommerce $ecommerce)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ecommerce  $ecommerce
     * @return \Illuminate\Http\Response
     */
    public function destroy(ecommerce $ecommerce)
    {
        //
    }
}
