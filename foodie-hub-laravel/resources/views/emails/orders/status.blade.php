<x-mail::message>
# Order Status Updated

Hi {{ $order->user->name }},

Your order `#{{ $order->id }}` status has been updated to **{{ strtoupper($order->status) }}**.

@if($order->status == 'delivered')
Enjoy your meal! 🍔
@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
