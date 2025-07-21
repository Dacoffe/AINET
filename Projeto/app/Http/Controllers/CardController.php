<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Card;
use Illuminate\Support\Facades\DB;

class CardController extends Controller
{
    public function show()
    {
        $card = Card::find(Auth::id());
        $user = Auth::user();

        return view('profile.card', compact('card', 'user'));
    }

    public function load(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1|max:500',
            'payment_method' => 'required|in:card,mbway,visa,paypal',
        ]);

        $user = Auth::user();


        // Cria ou obtém o cartão (ajusta o campo se necessário)
        $card = Card::firstOrCreate(
            ['id' => $user->id],
            ['card_number' => $this->generateCardNumber(), 'balance' => 0]
        );

        $newBalance = $card->balance + $request->amount;

        // Só pending_member tem restrição de mensalidade
        if ($user->type === 'pending_member') {
            if ($newBalance < 100) {
                $card->increment('balance', $request->amount);
                return redirect()->route('card.show')
                    ->with('error', 'Saldo insuficiente para pagar a mensalidade. Total atual: ' . $card->balance . ' créditos.');
            } else {
                $card->increment('balance', $request->amount);
                return $this->payMonthFee($card, $user);
            }
        }

        // Todos os outros (incluindo employee) carregam saldo normalmente
        $card->increment('balance', $request->amount);

        return redirect()->route('card.show')
            ->with('success', 'Saldo carregado com sucesso!');
    }


    private function generateCardNumber()
    {
        return Card::max('card_number') + 1;
    }

    public function topUp(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1|max:500',
        ]);

        $card = Card::find(Auth::id());
        $card->increment('balance', $request->amount);

        return redirect()->route('card.show')->with('success', 'Card loaded successfully!');
    }

    /**
     * Paga a mensalidade e atualiza a validade até o final do mês atual
     */
    public function payMonthFee($card = null, $user = null)
    {
        $user = $user ?? Auth::user();
        $card = $card ?? Card::find($user->id);
        $monthFee = 100; // Valor da mensalidade

        if ($user->type !== 'pending_member') {
            return redirect()->route('card.show')->with('error', 'Apenas membros pendentes podem pagar a mensalidade inicial.');
        }

        DB::transaction(function () use ($user, $card, $monthFee) {
            // Deduz o valor da mensalidade
            $card->decrement('balance', $monthFee);

            // Atualiza o tipo de usuário e define a validade até o final do mês atual
            $user->update([
                'type' => 'member',
                'valid_until' => now()->endOfMonth() // Define para o último dia do mês atual
            ]);
        });

        return redirect()->route('card.show')->with('success', 'Mensalidade paga. Agora é membro válido até ' . now()->endOfMonth()->format('d/m/Y') . '!');
    }

    public function checkBalance(Request $request)
    {
        $user = Auth::user();
        $card = Card::where('user_id', $user->id)->first();
        $amount = $request->input('amount');

        return response()->json([
            'has_balance' => $card && $card->balance >= $amount,
            'balance' => $card ? $card->balance : 0
        ]);
    }

    public function refund(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'order_id' => 'required|exists:orders,id'
        ]);

        $card = Card::findOrFail(Auth::id());

        DB::transaction(function () use ($card, $request) {
            $card->increment('balance', $request->amount);

            DB::table('card_transactions')->insert([
                'card_id' => $card->id,
                'amount' => $request->amount,
                'type' => 'refund',
                'description' => 'Reembolso manual da encomenda #' . $request->order_id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        });

        return back()->with('success', 'Saldo devolvido com sucesso');
    }

    public function transactions()
    {
        $card = Card::findOrFail(Auth::id());
        $transactions = DB::table('card_transactions')
            ->where('card_id', $card->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('profile.card_transactions', compact('card', 'transactions'));
    }
}
