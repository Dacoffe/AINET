<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = session()->get('cart', []);
        $total = array_reduce($cartItems, function($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Product $product, Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity;
        }/* elseif($product->name == 'Apple'){
            $product = Product::where('name', 'Banana')->first();
            $cart[$product->id] = [
                "id" => $product->id,
                "name" => $product->name,
                "price" => $product->price,
                "image_url" => $product->image_url ?? asset('images/default-product.png'),
                "quantity" => $request->quantity
            ];
        } */
        else{
            $cart[$product->id] = [
                "id" => $product->id,
                "name" => $product->name,
                "price" => $product->price,
                "image_url" => $product->image_url ?? asset('images/default-product.png'),
                "quantity" => $request->quantity
            ];
        }


        session()->put('cart', $cart);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'cart_count' => count($cart),
                'message' => 'Product added to cart!'
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart!');
    }

   public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0' // Permite zero
        ]);

        $cart = session()->get('cart', []);

        if ($request->quantity == 0) {
            if (isset($cart[$id])) {
                unset($cart[$id]);
                session()->put('cart', $cart);
                return redirect()->route('cart.index')
                    ->with('success', 'Product removed from cart!');
            }
        }
        elseif (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            return redirect()->route('cart.index')
                ->with('success', 'Cart updated!');
        }

        return redirect()->route('cart.index')
            ->with('error', 'Product not found in cart!');
    }

    public function remove($id)
    {
        $cart = session()->get('cart');

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'cart_count' => count($cart),
                'message' => 'Product removed from cart!'
            ]);
        }

        return redirect()->back()->with('success', 'Product removed from cart!');
    }
    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Cart has been cleared!');
    }
}
