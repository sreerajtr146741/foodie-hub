<x-mail::message>
# Order Placed Successfully!

Hi {{ $order->user->name }},

Thank you for your order! We have received your order `#{{ $order->id }}` and it is currently **{{ $order->status }}**.

### Order Summary:
@foreach($order->items as $item)
- **{{ $item->food->name }}** x {{ $item->quantity }} (${{ $item->price }})
@endforeach

**Total Amount: ${{ $order->total_amount }}**

We will notify you once your food is ready!

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
