{{-- filepath: resources/views/profile/card.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-center py-6">
        <div class="w-full max-w-4xl shadow rounded-lg bg-white p-6">
            <h3 class="text-lg sm:text-xl font-semibold text-white bg-green-700 px-6 py-3 rounded-t mb-6">
                Member Card
            </h3>

            @if (session('success'))
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4 text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Card Preview -->
            <div class="flex justify-center mb-8">
                <div
                    class="relative bg-gradient-to-r from-green-600 to-green-400 text-white w-80 h-48 rounded-xl shadow-lg p-4">
                    <div class="absolute top-4 left-4 text-xs sm:text-sm font-semibold">
                        Balance: {{ number_format($card->balance ?? 0, 2) }} €
                    </div>
                    <div class="flex flex-col justify-center items-center h-full text-center">
                        <div class="text-base font-semibold">{{ $user->name }}</div>
                        <div class="text-sm mt-1">Member Nº: {{ $user->id }}</div>
                        <div class="text-sm mt-1">Status: {{ $user->type }}</div>
                        <div class="text-sm mt-2">
                            @if ($user->valid_until)
                                @if ($user->valid_until->lt($now))
                                    <span>Fee expired on: {{ $user->valid_until->format('d/m/Y') }}</span>
                                @else
                                    <span>Fee valid until: {{ $user->valid_until->format('d/m/Y') }}</span>
                                @endif
                            @else
                                <span>Fee validity: N/A</span>
                            @endif
                        </div>
                    </div>
                    <div class="absolute bottom-4 right-4 text-xs italic opacity-80">
                        MyCard+
                    </div>
                </div>
            </div>

            <!-- Load Balance -->
            <div class="max-w-md mx-auto">
                <h4 class="text-md font-semibold mb-2">Load Balance</h4>
                <form method="POST" action="{{ route('card.load', 'amount') }}" class="space-y-4" id="paymentForm">
                    @csrf
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1">Amount (€):</label>
                        <input type="number" name="amount" min="1" step="0.01" required
                            class="w-full border border-gray-300 rounded px-3 py-2 bg-white text-sm focus:ring-green-500 focus:border-green-500"
                            placeholder="Ex: 50.00">
                    </div>

                    <!-- Default Payment Method -->
                    @if (!empty($user->default_payment))
                        <div class="mb-2">
                            <span class="text-sm text-gray-700 font-medium">Default Payment: </span>
                            <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold">
                                {{ ucfirst($user->default_payment) }}
                            </span>
                        </div>
                    @endif

                    <!-- Payment Method Section -->
                    <div class="mt-4">
                        <h3 class="text-md font-medium mb-2">Payment Method</h3>
                        <div class="space-y-2">

                            <div>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="payment_method" value="mbway"
                                        class="form-radio text-green-600" onclick="showPaymentFields('mbway')" required>
                                    <span class="ml-2">MBWay</span>
                                </label>
                            </div>
                            <div>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="payment_method" value="visa"
                                        class="form-radio text-green-600" onclick="showPaymentFields('visa')" required>
                                    <span class="ml-2">Visa</span>
                                </label>
                            </div>
                            <div>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="payment_method" value="paypal"
                                        class="form-radio text-green-600" onclick="showPaymentFields('paypal')" required>
                                    <span class="ml-2">PayPal</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Fields -->
                    <div id="payment-fields" class="space-y-2 mt-4">
                        <!-- Virtual Card: nada extra -->
                        <div id="fields-card"></div>
                        <!-- MBWay -->
                        <div id="fields-mbway" style="display:none;">
                            <label class="block text-gray-700 text-sm font-medium mb-1">Phone Number:</label>
                            <input type="text" name="mbway_phone" pattern="9[0-9]{8}" maxlength="9"
                                class="w-full border border-gray-300 rounded px-3 py-2 bg-white text-sm"
                                placeholder="Ex: 912345678">
                        </div>
                        <!-- Visa -->
                        <div id="fields-visa" style="display:none;">
                            <label class="block text-gray-700 text-sm font-medium mb-1">Card Number:</label>
                            <input type="text" name="visa_card_number" pattern="[1-9][0-9]{15}" maxlength="16"
                                class="w-full border border-gray-300 rounded px-3 py-2 bg-white text-sm"
                                placeholder="16 digits, not starting with 0">
                            <label class="block text-gray-700 text-sm font-medium mb-1 mt-2">CVC Code:</label>
                            <input type="text" name="visa_cvc" pattern="[1-9][0-9]{2}" maxlength="3"
                                class="w-full border border-gray-300 rounded px-3 py-2 bg-white text-sm"
                                placeholder="3 digits, not starting with 0">
                        </div>
                        <!-- PayPal -->
                        <div id="fields-paypal" style="display:none;">
                            <label class="block text-gray-700 text-sm font-medium mb-1">PayPal Email:</label>
                            <input type="email" name="paypal_email"
                                class="w-full border border-gray-300 rounded px-3 py-2 bg-white text-sm"
                                placeholder="your@email.pt">
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <a href="{{ url()->previous() }}"
                            class="bg-blue-700 hover:bg-blue-900 text-white font-bold py-2 px-4 rounded shadow transition inline-flex items-center">
                            Back
                        </a>
                        <button type="submit"
                            class="bg-green-700 hover:bg-green-800 text-white font-semibold px-5 py-2 rounded shadow text-sm">
                            @if ($user->type === 'pending_member')
                                Pay Fee
                            @else
                                Load Card
                            @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showPaymentFields(method) {
            document.getElementById('fields-card').style.display = method === 'card' ? 'block' : 'none';
            document.getElementById('fields-mbway').style.display = method === 'mbway' ? 'block' : 'none';
            document.getElementById('fields-visa').style.display = method === 'visa' ? 'block' : 'none';
            document.getElementById('fields-paypal').style.display = method === 'paypal' ? 'block' : 'none';
        }

        // Inicialização: mostra apenas o campo do método selecionado
        document.addEventListener('DOMContentLoaded', function() {
            let checked = document.querySelector('input[name="payment_method"]:checked');
            if (checked) showPaymentFields(checked.value);
        });

        // Validação extra no submit (opcional, para garantir UX)
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            let method = document.querySelector('input[name="payment_method"]:checked').value;
            if (method === 'mbway') {
                let phone = document.querySelector('input[name="mbway_phone"]').value;
                if (!/^9\d{8}$/.test(phone) || phone.endsWith('2')) {
                    alert('Número MBWay inválido.');
                    e.preventDefault();
                }
            }
            if (method === 'visa') {
                let card = document.querySelector('input[name="visa_card_number"]').value;
                let cvc = document.querySelector('input[name="visa_cvc"]').value;
                if (!/^[1-9]\d{15}$/.test(card) || card.endsWith('2')) {
                    alert('Número de cartão VISA inválido.');
                    e.preventDefault();
                }
                if (!/^[1-9]\d{2}$/.test(cvc) || cvc.endsWith('2')) {
                    alert('CVC VISA inválido.');
                    e.preventDefault();
                }
            }
            if (method === 'paypal') {
                let email = document.querySelector('input[name="paypal_email"]').value;
                if (!/^[^@]+@[^@]+\.(pt|com)$/.test(email)) {
                    alert('Email PayPal inválido (tem de terminar em .pt ou .com).');
                    e.preventDefault();
                }
            }
        });
    </script>
@endsection
