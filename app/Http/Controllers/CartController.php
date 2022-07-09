<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
// use Illuminate\Contracts\Session\Session as SessionSession;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function cartList()
    {
       
        $data['pageTitle'] = "Cart";
        $data['bannerTitle'] = Banner::where('page',"cart")->first();
    
        // $items = \Cart::getContent();
        // dd($items);
        
        return view('frontend.add-to-cart', $data);
    }


    public function addToCart(Request $request ,$id)
    {
       
        
        $product = Product::findOrFail($id);
         if($product->sale_price == 0 ){
            $price = $product->regular_price;
        }else{
            $price = $product->sale_price;
        }
        \Cart::add(array(
            'id' => $product->id,
            'name' => $product->title,
            'price' => $price,
            'quantity' => 1,
            'attributes' => array(),
            'associatedModel' => $product
        ));

        $data['bannerTitle'] = Banner::where('page',"travel-packages")->first();
        // Session()->put('cart' ,$items);
        Session()->flash('success', 'Product is Added to Cart Successfully !');
         
        return redirect()->route('front.cart.list');
    }

    public function updateCart(Request $request)
    {
       
        $product = Product::findOrFail($request->id);
        \Cart::update($request->id,[
            'quantity' => array(
                'relative' => false,
                'value' =>$request->quantity,
            ),
        ]);
        // $items = \Cart::getContent();
       
        // Session()->put('cart' ,$items);
        session()->flash('success', 'Item Cart is Updated Successfully !');
        return redirect()->route('front.cart.list');
    }

    public function removeCart($id)
    {
        // dd($id);
            // $cart = Session()->get('cart');
            \Cart::remove($id);
            // unset($cart[$id]);
            // session()->flush('success', 'All Item Cart Clear Successfully !');

            // Session()->put('cart', $cart);
             return redirect()->route('front.cart.list');
        
    }

    public function clearAllCart()
    {
        
         \Cart::clear();

        session()->flush('success', 'All Item Cart Clear Successfully !');

        return redirect()->route('front.product.promotion');
    }
}
