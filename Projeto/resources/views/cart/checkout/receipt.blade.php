<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            color: #1f2937;
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }
        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: #065f46;
        }
        .invoice-info {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }
        .info-label {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }
        .info-value {
            font-weight: 500;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }
        .table th {
            background-color: #f9fafb;
            text-align: left;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            color: #374151;
            border: 1px solid #e5e7eb;
        }
        .table td {
            padding: 1rem;
            border: 1px solid #e5e7eb;
            vertical-align: top;
        }
        .text-right {
            text-align: right;
        }
        .total-row {
            font-weight: 600;
            background-color: #f3f4f6;
        }
        .footer {
            margin-top: 3rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 0.875rem;
            color: #6b7280;
        }
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: capitalize;
        }
        .badge-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .badge-completed {
            background-color: #d1fae5;
            color: #065f46;
        }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="logo">{{ config('app.name') }}</div>
            <div style="font-size: 0.875rem; color: #6b7280;">Order Invoice</div>
        </div>
        <div style="text-align: right;">
            <div style="font-size: 1.25rem; font-weight: 600; color: #065f46;">Order Number</div>
            <div style="font-size: 0.875rem; color: #6b7280;">#{{ $order->id }}</div>
        </div>
    </div>

    <div class="invoice-info">
        <div>
            <div class="info-label">Order Date</div>
            <div class="info-value">{{ $order->created_at->format('M d, Y H:i') }}</div>
        </div>
        <div>
            <div class="info-label">Status</div>
            <div>
                <span class="badge badge-{{ $order->status }}" style="margin-top: 0.25rem;">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
        </div>
        <div>
            <div class="info-label">Payment Method</div>
            <div class="info-value">Club Card</div>
        </div>
        <div>
            <div class="info-label">NIF</div>
            <div class="info-value">{{ $order->nif ?? 'N/A' }}</div>
        </div>
    </div>

    <div style="margin-bottom: 1.5rem;">
        <div style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 0.5rem;">Shipping Address</div>
        <div style="color: #374151;">{{ $order->delivery_address }}</div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th style="width: 45%;">Product</th>
                <th>Unit Price</th>
                <th>Qty</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ number_format($product->pivot->unit_price, 2, '.', ',') }} €</td>
                <td>{{ $product->pivot->quantity }}</td>
                <td class="text-right">{{ number_format($product->pivot->subtotal, 2, '.', ',') }} €</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right" style="border: none; padding-top: 1rem;">Subtotal</td>
                <td class="text-right" style="border: none; padding-top: 1rem;">{{ number_format($order->total_items, 2, '.', ',') }} €</td>
            </tr>
            <tr>
                <td colspan="3" class="text-right" style="border: none;">Shipping</td>
                <td class="text-right" style="border: none;">{{ number_format($order->shipping_cost, 2, '.', ',') }} €</td>
            </tr>
            <tr class="total-row">
                <td colspan="3" class="text-right" style="border: none; padding-bottom: 1rem;">Total</td>
                <td class="text-right" style="border: none; padding-bottom: 1rem;">{{ number_format($order->total, 2, '.', ',') }} €</td>
            </tr>
            <tr class="total-row">
                <td colspan="3" class="text-right" style="border: none;">Card Balance Deduction</td>
                <td class="text-right" style="border: none; color: #dc2626;">-{{ number_format($order->total, 2, '.', ',') }} €</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Thank you for your purchase!<br>
        {{ config('app.name') }} &copy; {{ date('Y') }} | All rights reserved
    </div>
</body>
</html>
