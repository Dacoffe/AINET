<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryFormRequest;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryController extends Controller
{
    protected $pagination = 25;

    public function index(Request $request)
    {
        $sortBy = $request->get('sort_by', 'id'); // Coluna padrão
        $order = $request->get('order', 'asc'); // Ordem padrão

        // Validação simples para evitar SQL Injection
        $allowedSorts = ['id', 'date', 'status', 'total'];
        $allowedOrders = ['asc', 'desc'];

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        if (!in_array($order, $allowedOrders)) {
            $order = 'asc';
        }

        $categories = Category::orderBy($sortBy, $order)->paginate($this->pagination);

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        $newCategorie = new Category();
        return view('categories.create')->with('category', $newCategorie);
    }

    public function store(CategoryFormRequest $request)
    {
        $category = Category::create($request->validated());

        if ($request->hasFile('image')) {
            $category->update([
                'image_url' => $request->file('image')->store('categories', 'public')
            ]);
        }

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully');
    }

    public function show(Category $category)
    {
        $products = $category->products()->get();
        return view('categories.show', compact('category', 'products'));
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(CategoryFormRequest $request, Category $category)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            // Apaga imagem antiga se necessário
            if ($category->image && Storage::disk('public')->exists("categories/{$category->image}")) {
                Storage::disk('public')->delete("categories/{$category->image}");
            }
            $filename = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->storeAs('categories', $filename, 'public');
            $validated['image'] = $filename;
        }

        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        $categoryName = $category->name;

        try {
            // Soft delete (preenche deleted_at), mesmo que tenha produtos associados
            $category->delete();

            $alertType = 'success';
            $alertMsg = "Category <u>{$categoryName}</u> has been deleted successfully!";
        } catch (\Exception $error) {
            $alertType = 'danger';
            $alertMsg = "It was not possible to delete the category <u>{$categoryName}</u> because there was an error with the operation!";
        }

        return redirect()->route('categories.index')
            ->with('alert-type', $alertType)
            ->with('alert-msg', $alertMsg);
    }
}
