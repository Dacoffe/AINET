<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductFormRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Exception;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $pagination = 25; // Define the number of products per page
    public function index(Request $request)
    {
        $sortBy = $request->get('sort_by', 'name');
        $order = $request->get('order', 'asc');
        $search = $request->get('search');

        $allowedSorts = ['name', 'price', 'stock', 'discount'];
        $allowedOrders = ['asc', 'desc'];

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'name';
        }

        if (!in_array($order, $allowedOrders)) {
            $order = 'asc';
        }

        $products = Product::query();

        if ($search) {
            $products->where('name', 'like', '%' . $search . '%');
        }

        $products = $products->orderBy($sortBy, $order)
            ->paginate($this->pagination)
            ->appends(['search' => $search]);

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $newProduct = new Product();
        $categories = Category::all();
        return view('products.create')->with('product', $newProduct)->with('categories', $categories);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductFormRequest $request)
    {
        $request->validated();
        $validated = $request->all();
        $validated['photo'] = 'product_no_image.png'; // Default photo
        if ($request->hasFile('photo')) {
            // Guardar foto
            $filename = uniqid() . '.' . $request->file('photo')->getClientOriginalExtension();
            $request->file('photo')->storeAs('products', $filename, 'public');
            $validated['photo'] = $filename;
        }
        $product = Product::create($validated);
        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $category = Category::find($product->category_id);

        return view('products.show', ['product' => $product, 'category' => $category]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $category = Category::find($product->category_id);

        return view('products.edit', ['product' => $product, 'category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductFormRequest $request, Product $product)
    {
        $validated = $request->validated();

        if ($request->hasFile('photo')) {
            // Apagar foto anterior se não for "anonymous.png"
            if ($product->photo && $product->photo !== 'product_no_image.png' && Storage::disk('public')->exists("products/{$product->photo}")) {
                Storage::disk('public')->delete("products/{$product->photo}");
            }

            // Guardar nova foto
            $filename = uniqid() . '.' . $request->file('photo')->getClientOriginalExtension();
            $request->file('photo')->storeAs('products', $filename, 'public');
            $validated['photo'] = $filename;
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    //delete stock from product
     public function destroyStock(Product $product)
    {
        $productName = $product->name;

        try {
            $product->stock = 0;
            $product->save();

            $alertType = 'success';
            $alertMsg = "Product <u>{$productName}</u> has been updated to stock 0 successfully!";
        } catch (\Exception $error) {
            $alertType = 'danger';
            $alertMsg = "It was not possible to delete the stock from product <u>{$productName}</u> because there was an error with the operation!";
        }

        return redirect()->route('products.index')
            ->with('alert-type', $alertType)
            ->with('alert-msg', $alertMsg);
    }

    public function destroy(Product $product)
    {
        $productName = $product->name;

        try {
            // Soft delete (preenche deleted_at)
            $product->delete();

            $alertType = 'success';
            $alertMsg = "Product <u>{$productName}</u> has been deleted successfully!";
        } catch (\Exception $error) {
            $alertType = 'danger';
            $alertMsg = "It was not possible to delete the product <u>{$productName}</u> because there was an error with the operation!";
        }

        return redirect()->route('products.index')
            ->with('alert-type', $alertType)
            ->with('alert-msg', $alertMsg);
    }
}
