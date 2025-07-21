<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Card;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Mail\OrderCompletedMail;
use App\Mail\OrderReceiptMail;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    protected $pagination = 25;
    public function index(Request $request)
    {
        $sortBy = $request->get('sort_by', 'id'); // Coluna padrão
        $order = $request->get('order', 'asc'); // Ordem padrão
        $search = $request->get('search'); // Novo parâmetro de busca


        // Validação simples para evitar SQL Injection
        $allowedSorts = ['id', 'date', 'status', 'total'];
        $allowedOrders = ['asc', 'desc'];

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        if (!in_array($order, $allowedOrders)) {
            $order = 'asc';
        }

        $orders = Order::query();

        // Adicionar busca por número do pedido
        if ($search) {
            $orders->where('id', 'like', '%' . $search . '%');
        }

        $orders = $orders->orderBy($sortBy, $order)
            ->paginate($this->pagination)
            ->appends(['search' => $search]);

        return view('orders.index', compact('orders'));
    }

    public function my_orders(Request $request)
    {
        $sortBy = $request->get('sort_by', 'id'); // Coluna padrão
        $order = $request->get('order', 'asc'); // Ordem padrão

        // Validação simples para evitar SQL Injection
        $allowedSorts = ['id', 'date', 'total', 'status'];
        $allowedOrders = ['asc', 'desc'];

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        if (!in_array($order, $allowedOrders)) {
            $order = 'asc';
        }

        $user = Auth::user();
        $orders = $user->orders()->orderBy($sortBy, $order)->paginate($this->pagination);

        return view('profile.my_orders', compact('orders'));
    }

    public function show_Order(Order $order)
    {
        $product = $order->products;
        return view('orders.show', compact('order', 'product'));
    }

    public function showOrders(Order $order)
    {
        return view('orders.show', compact('order'));
    }
    public function edit(Order $order)
    {
        $products = $order->products;
        $canEditShipping = Auth::user()->type === 'board' && $order->status === 'pending';
        return view('orders.edit', [
            'order' => $order,
            'products' => $products,
            'canEditShipping' => $canEditShipping,
            'mode' => 'edit'
        ]);
    }

    public function showCancelForm(Order $order)
    {
        if (Auth::id() !== $order->member_id || $order->status !== 'pending') {
            abort(403);
        }

        return view('orders.cancel', compact('order'));
    }

    public function cancel(Request $request, Order $order)
    {
        $request->validate([
            'cancel_reason' => 'required|string|max:255'
        ]);

        if (Auth::id() !== $order->member_id) {
            return redirect()->route('profile.my_orders')->with('error', 'Não tem autorização para cancelar esta encomenda.');
        }

        if ($order->status !== 'pending') {
            return redirect()->route('profile.my_orders')->with('error', 'Apenas encomendas pendentes podem ser canceladas.');
        }

        DB::beginTransaction();

        try {
            // Bloqueia o cartão
            $card = Card::lockForUpdate()->find(Auth::id());
            if (!$card) {
                throw new \Exception('Cartão não encontrado para este utilizador.');
            }

            $refundAmount = $order->total;

            // Reembolso
            if ($order->payment_method === 'card') {
                $card->increment('balance', $refundAmount);

                DB::table('card_transactions')->insert([
                    'card_id' => $card->id,
                    'amount' => $refundAmount,
                    'type' => 'refund',
                    'description' => 'Reembolso da encomenda #' . $order->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Repor stock
            foreach ($order->products as $product) {
                $product->stock += $product->pivot->quantity;
                $product->save();
            }

            // Atualiza o estado da encomenda
            $order->update([
                'status' => 'canceled',
                'cancel_reason' => $request->cancel_reason,
                'updated_at' => now()
            ]);


            // Buscar o utilizador autenticado
            $user = Auth::user();

            DB::commit();

            // Atualiza o balance do cartão com o valor total da encomenda
            $refundAmount = $order->total;
            $card->increment('balance', $refundAmount);

            \DB::table('operations')->insert([
                'card_id'           => $card->id,
                'type'              => 'credit',
                'value'             => $refundAmount, // normalmente $order->total
                'date'              => now()->toDateString(),
                'debit_type'        => null,
                'credit_type'       => 'order_cancellation',
                'payment_type'      => $order->payment_method,
                'payment_reference' => null,
                'order_id'          => $order->id,
                'custom'            => null,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);


            $successMsg = "Encomenda #{$order->id} cancelada com sucesso. Reembolsado €" . number_format($order->total, 2);

            // Redireciona para orders.index se veio do edit, senão mantém comportamento antigo
            if ($request->has('from_edit')) {
                return redirect()->route('orders.index')->with('success', $successMsg);
            }
            return redirect()->route('card.show')->with('success', $successMsg);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erro ao cancelar encomenda #{$order->id}: " . $e->getMessage());
            return redirect()->route('profile.my_orders')->with('error', 'Erro ao cancelar a encomenda: ' . $e->getMessage());
        }
    }


    public function pending(Request $request)
    {
        // Só employee
        if (!in_array(Auth::user()->type, ['employee',])) {
            abort(403, 'Apenas employee pode aceder a esta página.');
        }

        $sortBy = $request->get('sort_by', 'id');
        $order = $request->get('order', 'asc');

        $allowedSorts = ['id', 'date', 'total'];
        $allowedOrders = ['asc', 'desc'];

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }
        if (!in_array($order, $allowedOrders)) {
            $order = 'asc';
        }

        $orders = Order::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate($this->pagination);

        return view('orders.pending', compact('orders'));
    }


    public function accept(Order $order)
    {
        // Só employee pode aceitar
        if (!in_array(Auth::user()->type, ['employee'])) {
            abort(403, 'Apenas employee pode aceitar as encomendas.');
        }

        // Verificar todos os produtos sem stock suficiente
        $outOfStock = [];
        foreach ($order->products as $product) {
            if ($product->stock < $product->pivot->quantity) {
                $outOfStock[] = "{$product->name} (Disponível: {$product->stock}, Necessário: {$product->pivot->quantity})";
            }
        }

        if (count($outOfStock) > 0) {
            return redirect()->back()
                ->with('error', 'Não é possível aceitar a encomenda. Falta de stock nos seguintes produtos: ' . implode(', ', $outOfStock));
        }

        DB::beginTransaction();

        try {
            // Atualizar o stock dos produtos
            foreach ($order->products as $product) {
                $product->stock -= $product->pivot->quantity;
                $product->save();
            }

            // Atualizar o status da encomenda
            $order->update([
                'status' => 'completed',
                'updated_at' => now()
            ]);

            // Enviar email ao cliente
            if ($order->relationLoaded('member') || method_exists($order, 'member')) {
                $user = $order->member;
            } else {
                $user = User::find($order->member_id);
            }
            if ($user && $user->email) {
                Mail::to($user->email)->send(new OrderCompletedMail($order));
            }

            DB::commit();

            return redirect()->route('orders.pending')
                ->with('success', "Order #{$order->id} has been accepted and marked as completed.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error accepting order #{$order->id}: " . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error accepting the order: ' . $e->getMessage());
        }
    }
    public function publicReceipt(Order $order)
    {
        $pdfPath = base_path('database/seeders/receipts/' . $order->pdf_receipt);

        if (!file_exists($pdfPath)) {
            abort(404);
        }

        return response()->download($pdfPath, 'receipt_' . $order->id . '.pdf');
    }
    public function update(Request $request, Order $order)
    {
        $rules = [];

        // Só permite editar shipping se for board e pending
        if (Auth::user()->type === 'board' && $order->status === 'pending') {
            $rules['shipping_cost'] = 'required|numeric|min:0';
        }

        // Valida apenas se houver campos para validar
        if (!empty($rules)) {
            $validated = $request->validate($rules);
        } else {
            $validated = [];
        }

        $changed = false;

        // Atualiza o shipping_cost se permitido
        if (isset($validated['shipping_cost'])) {
            if ($order->shipping_cost != $validated['shipping_cost']) {
                // Remove o shipping antigo do total e soma o novo
                $order->total = $order->total - $order->shipping_cost + $validated['shipping_cost'];
                $order->shipping_cost = $validated['shipping_cost'];
                $changed = true;
            }
        }

        // Se não houve alterações, retorna com info
        if (!$changed) {
            return redirect()->back()->with('info', 'No changes were made.');
        }

        $order->save();

        return redirect()->route('orders.edit', $order->id)
            ->with('success', 'Order updated successfully!');
    }
}
