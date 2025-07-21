@component('mail::message')
# Order Receipt

@if ($status === 'pending')
Your order #{{ $order->id }} has been received and is pending approval. We'll notify you once it's been processed.
@else
Your order #{{ $order->id }} has been completed and is being prepared for shipment.
@endif

**Order Details:**
- **Date:** {{ $order->created_at->format('M d, Y H:i') }}
- **Total:** {{ number_format($order->total, 2) }} €
- **Status:** {{ ucfirst($order->status) }}

@component('mail::button', ['url' => route('profile.my_orders')])
View Orders
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
