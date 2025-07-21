@component('mail::message')
# Order Completed

Your order #{{ $order->id }} has been completed and is being prepared for shipment.

**Order Details:**
- **Date:** {{ $order->created_at->format('M d, Y H:i') }}
- **Total:** {{ number_format($order->total, 2) }} €
- **Shipping Address:** {{ $order->delivery_address }}

@component('mail::button', ['url' => route('profile.my_orders')])
View Orders
@endcomponent

@component('mail::button', ['url' => route('orders.public_receipt', $order->id)])
Download Receipt (PDF)
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
