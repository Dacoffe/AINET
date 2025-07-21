<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Card;
use App\Models\User;
use App\Http\Controllers\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderReceiptMail;
use PDF;


class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = Session::get('cart', []);

        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $subtotal = $this->calculateSubtotal($cartItems);
        $shipping = $this->calculateShipping($cartItems);
        $total = $subtotal + $shipping;

        $user = Auth::user();

        if (!$user) {
            Session::put('checkout_redirect', true);
            return redirect()->route('login')->with('warning', 'Please login as a club member to complete your purchase.');
        }

        if (!$user->isClubMember() && !$user->isEmployee()) {
            return redirect()->route('cart.index')->with('error', 'Only club members and employees can make purchases.');
        }

        // Get card balance from Card model
        $card = Card::find($user->id);
        $cardBalance = $card ? $card->balance : 0;
        $canCheckout = $cardBalance >= $total;

        // Check for out of stock items
        $outOfStockItems = [];
        foreach ($cartItems as $id => $item) {
            $product = Product::find($id);
            if ($product && $product->stock < $item['quantity']) {
                $outOfStockItems[$id] = $product->stock;
            }
        }

        $defaultData = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? '',
            'default_delivery_address' => $user->default_delivery_address ?? '',
            'nif' => $user->nif ?? '',
        ];

        // Só mostra o método de pagamento predefinido
        $defaultPaymentType = $user->default_payment_type ?? 'card';

        return view('cart.checkout.index', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
            'defaultData' => $defaultData,
            'cardBalance' => $cardBalance,
            'canCheckout' => $canCheckout,
            'outOfStockItems' => $outOfStockItems,
            'defaultPaymentType' => $defaultPaymentType,
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'phone'   => 'required|string|max:20',
            'nif'     => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'O carrinho está vazio.');
        }

        // Verificar stock e calcular total
        $totalItems = 0;
        $subtotal = 0;
        $outOfStock = [];

        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if (!$product || $product->stock < $item['quantity']) {
                $outOfStock[] = $item;
            } else {
                $quantity = $item['quantity'];
                $totalItems += $quantity;
                $subtotal += $item['price'] * $quantity;
            }
        }

        if (!empty($outOfStock)) {
            return redirect()->route('cart.checkout.index')
                ->with('error', 'Some products are out of stock.');
        }

        $shippingCost = $this->calculateShipping($cart);
        $total = $subtotal + $shippingCost;

        // Verificar saldo do cartão
        $card = Card::find($user->id);
        if (!$card || $card->balance < $total) {
            return redirect()->back()->with('error', 'Insufficient balance on the card.');
        }

        DB::beginTransaction();

        try {
            // Criar a encomenda
            $order = Order::create([
                'member_id'        => $user->id,
                'status'           => 'pending',
                'date'             => now()->toDateString(),
                'total_items'      => $subtotal,
                'shipping_cost'    => $shippingCost,
                'total'           => $total,
                'nif'             => $request->input('nif'),
                'delivery_address' => $request->input('address'),
                'payment_method'   => 'card',
            ]);

            // Adicionar produtos à encomenda
            foreach ($cart as $productId => $item) {
                $product = Product::findOrFail($productId);

                $order->products()->attach($productId, [
                    'quantity'   => $item['quantity'],
                    'unit_price' => $item['price'],
                    'discount'   => 0,
                    'subtotal'   => $item['price'] * $item['quantity'],
                ]);

                // Atualizar stock
                $product->stock -= $item['quantity'];
                $product->save();
            }

            // Deduzir valor do cartão
            $card->decrement('balance', $total);
            $card->save();

            \DB::table('operations')->insert([
                'card_id'           => $card->id,
                'type'              => 'debit',
                'value'             => $total,
                'date'              => now()->toDateString(),
                'debit_type'        => 'order',
                'credit_type'       => null,
                'payment_type'      => $order->payment_method,
                'payment_reference' => null,
                'order_id'          => $order->id,
                'custom'            => null,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            // Gerar PDF e enviar e-mail
            $pdf = PDF::loadView('cart.checkout.receipt', compact('order'));
            $filename = 'receipt_' . $order->id . '.pdf';
            $fullPath = base_path('database/seeders/receipts/') . $filename;

            // Guardar PDF
            file_put_contents($fullPath, $pdf->output());

            // Atualizar a ordem com o nome do ficheiro
            $order->update([
                'pdf_receipt' => $filename,
                'updated_at' => now()
            ]);

            // Enviar e-mail com o PDF anexado
            Mail::to($user)->send(new OrderReceiptMail($order, $fullPath));

            DB::commit();
            session()->forget('cart');

            return redirect()->route('cart.checkout.show', $order->id)
                ->with('success', 'Order completed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error processing order: ' . $e->getMessage());
        }
    }

    public function confirmation()
    {
        $orderId = Session::get('order_id');

        if (!$orderId) {
            return redirect()->route('home');
        }

        $order = Order::with('products')->find($orderId);
        $outOfStockItems = Session::get('out_of_stock_items', []);

        if (!$order) {
            return redirect()->route('home');
        }

        return view('cart.checkout.confirmation', [
            'order' => $order,
            'outOfStockItems' => $outOfStockItems
        ]);
    }

    public function show(Order $order)
    {
        // Verifica se o usuário tem permissão para ver este pedido
        if (Auth::id() !== $order->member_id && !Auth::user()->isEmployee() || !Auth::user()->isClubMember()) {
            abort(403);
        }

        return view('cart.checkout.show', compact('order'));
    }

    public function generatePdf(Order $order)
    {
        // Verificação de permissões
        if (Auth::id() !== $order->member_id && !Auth::user()->isEmployee() || !Auth::user()->isClubMember()) {
            abort(403);
        }

        // Caminho dos recibos
        $receiptPath = base_path('database/seeders/receipts');

        // Obter todos os ficheiros existentes
        $files = scandir($receiptPath);

        // Filtrar os que começam com "receipt_" e terminam com ".pdf"
        $receiptNumbers = [];
        foreach ($files as $file) {
            if (preg_match('/^receipt_(\d+)\.pdf$/', $file, $matches)) {
                $receiptNumbers[] = (int)$matches[1];
            }
        }

        // Obter o maior número atual, ou começar do 1
        $lastNumber = empty($receiptNumbers) ? 0 : max($receiptNumbers);
        $nextNumber = $lastNumber + 1;

        // Gerar PDF
        $pdf = PDF::loadView('cart.checkout.receipt', compact('order'));

        // Nome do novo ficheiro
        $filename = 'receipt_' . $nextNumber . '.pdf';

        // Caminho completo do novo ficheiro
        $fullPath = $receiptPath . '/' . $filename;

        // Guardar PDF
        file_put_contents($fullPath, $pdf->output());

        // Atualizar a ordem com o nome do ficheiro
        $order->update([
            'pdf_receipt' => $filename,
            'updated_at' => now()
        ]);

        // Retornar o PDF para download
        return $pdf->download($filename);
    }

    public function showReceipt(Order $order)
    {
        if (Auth::id() !== $order->member_id && !Auth::user()->isClubMember()) {
            abort(403);
        }

        if (empty($order->pdf_receipt)) {
            abort(404, 'Receipt not available');
        }

        $filePath = Storage::disk('receipts')->path($order->pdf_receipt);

        if (!Storage::disk('receipts')->exists($order->pdf_receipt)) {
            abort(404, 'Receipt file not found');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    private function calculateSubtotal($cartItems)
    {
        return array_reduce($cartItems, function ($total, $item) {
            return $total + ($item['price'] * $item['quantity']);
        }, 0);
    }

    private function calculateShipping($cartItems)
    {
        $subtotal = $this->calculateSubtotal($cartItems);
        if ($subtotal > 100) {
            return 0.00;
        }
        if ($subtotal > 50) {
            return 5.00;
        }
        return count($cartItems) > 0 ? 10.00 : 0;
    }
}
