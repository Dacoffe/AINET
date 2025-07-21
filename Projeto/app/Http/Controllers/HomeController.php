<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $pagination = 12;

    /**
     * Display homepage with filtered products
     */
    public function index(Request $request)
    {
        $productsQuery = Product::query()->with('category');

        // Aplicar filtros
        $this->applyFilters($productsQuery, $request);

        // Aplicar ordenação
        $this->applySorting($productsQuery, $request);

        // Pagina primeiro
        $products = $productsQuery->paginate($this->pagination)->appends($request->query());

        $products->getCollection()->transform(function ($product) {
            $product->price *= 2;
            return $product;
        });

        $categories = Category::all();

        return view('home.index', compact('products', 'categories'));
    }

    /**
     * Display products by category
     */
    public function show(Category $category, Request $request)
    {
        $query = $category->products()->with('category');

        $this->applyFilters($query, $request);

        // Aplicar ordenação
        $this->applySorting($query, $request);

        $products = $query->paginate($this->pagination);

        $products->getCollection()->transform(function ($product) {
            $product->price *= 2;
            return $product;
        });

        return view('home.show', [
            'category' => $category,
            'products' => $products,
            'categories' => Category::all()
        ]);
    }

    /**
     * Search products
     */
   public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2'
        ]);

        $query = $request->input('q');

        $products = Product::query()
            ->when($query, function($q) use ($query) {
                return $q->where('name', 'like', "%$query%")
                       ->orWhereHas('category', function($q) use ($query) {
                           $q->where('name', 'like', "%$query%");
                       });
            })
            ->with('category')
            ->paginate($this->pagination);

        $products->getCollection()->transform(function ($product) {
            $product->price *= 2;
            return $product;
        });

       return view('home.search', [
        'products' => $products,
        'query' => $query,
        'categories' => Category::all()
]);

    }
        /**
     * Apply filters to the query
     */
    protected function applyFilters($query, Request $request)
    {
        $query->when($request->filled('category'), function($q) use ($request) {
            $q->where('category_id', $request->category);
        })->when($request->filled('min_price'), function($q) use ($request) {
            $q->where('price', '>=', $request->min_price);
        })->when($request->filled('max_price'), function($q) use ($request) {
            $q->where('price', '<=', $request->max_price);
        });
    }

    /**
     * Apply sorting to the query
     */
    protected function applySorting($query, Request $request)
    {
        $query->when($request->filled('sort'), function($q) use ($request) {
            switch ($request->sort) {
                case 'price_asc':
                    return $q->orderBy('price');
                case 'price_desc':
                    return $q->orderByDesc('price');
                case 'name_asc':
                    return $q->orderBy('name');
                case 'name_desc':
                    return $q->orderByDesc('name');
                default:
                    return $q->latest();
            }
        }, function($q) {
            return $q->latest();
        });
    }
}
