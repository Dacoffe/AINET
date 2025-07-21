<?php

namespace App\Http\Controllers;

use App\Models\Supply_Order;
use Illuminate\Http\Request;
use App\Models\Product;

class SupplyOrderController extends Controller
{
    // Listar todas as supply orders
    public function index()
    {
        $supplyOrders = Supply_Order::orderByDesc('created_at')->paginate(25);
        return view('supply_orders.index', compact('supplyOrders'));
    }

    // Mostrar uma supply order específica
    public function show(Supply_Order $supplyOrder)
    {
        return view('supply_orders.show', compact('supplyOrder'));
    }

    // Formulário de criação (opcional)
    public function create(Request $request)
    {
        $products = Product::orderBy('name')->get();
        $selectedProductId = $request->get('product_id');
        return view('supply_orders.create', compact('products', 'selectedProductId'));
    }

    // Guardar uma nova supply order
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'registered_by_user_id' => 'required|exists:users,id',
            'status' => 'required|in:requested,completed',
            'quantity' => 'required|integer|min:1',
        ]);

        // Cria a supply order
        $supplyOrder = \App\Models\Supply_Order::create($validated);

        // Só incrementa o stock se o status for 'completed'
        if ($validated['status'] === 'completed') {
            $product = \App\Models\Product::find($validated['product_id']);
            $product->stock += $validated['quantity'];
            $product->save();
        }

        return redirect()->route('supply_orders.index')->with('success', 'Supply order criada com sucesso!');
    }

    // Formulário de edição (opcional)
    public function edit(Supply_Order $supply_order)
    {
        return view('supply_orders.edit', compact('supply_order'));
    }

    // Atualizar uma supply order
    public function update(Request $request, Supply_Order $supply_order)
    {
        $validated = $request->validate([
            'status' => 'required|in:requested,completed',
            'quantity' => 'required|integer|min:1',
        ]);

        $supply_order->update($validated);

        return redirect()->route('supply_orders.index')->with('success', 'Supply order updated!');
    }

    public function markAsCompleted($id)
    {
        $supplyOrder = \App\Models\Supply_Order::findOrFail($id);

        if ($supplyOrder->status === 'requested') {
            $supplyOrder->status = 'completed';
            $supplyOrder->save();

            // Atualiza o stock do produto
            $product = \App\Models\Product::find($supplyOrder->product_id);
            if ($product) {
                $product->stock += $supplyOrder->quantity;
                $product->save();
            }
        }

        // Redireciona para o show (ou index) SEM usar back()

        return redirect()->route('supply_orders.show', $supplyOrder->id)
            ->with('success', 'Supply order completed!');
    }

    // Apagar uma supply order
    public function destroy(Supply_Order $supply_order)
    {
        $supply_order->delete();
        return redirect()->route('supply_orders.index')->with('success', 'Supply order deleted!');
    }
}
