<?php
namespace App\Http\Controllers;

use App\Models\Product;

class StockController extends Controller
{
    protected $pagination =25;

    public function index()
    {
        // Exemplo: produtos com stock < 10
        $products = Product::where('stock', '<', 10)->orderBy('stock', 'asc')->paginate($this->pagination);
        return view('products.stock', compact('products'));
    }
}
