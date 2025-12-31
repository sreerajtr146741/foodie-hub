<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body { font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; text-align: center; color: #777; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, .15); font-size: 16px; line-height: 24px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; color: #555; text-align: left; }
        .invoice-box table { width: 100%; line-height: inherit; text-align: left; }
        .invoice-box table td { padding: 5px; vertical-align: top; }
        .invoice-box table tr td:nth-child(2) { text-align: right; }
        .invoice-box table tr.top table td { padding-bottom: 20px; }
        .invoice-box table tr.top table td.title { font-size: 45px; line-height: 45px; color: #333; }
        .invoice-box table tr.information table td { padding-bottom: 40px; }
        .invoice-box table tr.heading td { background: #eee; border-bottom: 1px solid #ddd; font-weight: bold; }
        .invoice-box table tr.details td { padding-bottom: 20px; }
        .invoice-box table tr.item td{ border-bottom: 1px solid #eee; }
        .invoice-box table tr.item.last td { border-bottom: none; }
        .invoice-box table tr.total td:nth-child(2) { border-top: 2px solid #eee; font-weight: bold; }
        @media only screen and (max-width: 600px) { .invoice-box table tr.top table td { width: 100%; display: block; text-align: center; } .invoice-box table tr.information table td { width: 100%; display: block; text-align: center; } }
        /** RTL **/
        .rtl { direction: rtl; font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; }
        .rtl table { text-align: right; }
        .rtl table tr td:nth-child(2) { text-align: left; }
        
        @media print {
            .no-print { display: none; }
            .invoice-box { border: 0; box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #333; color: white; border: none; cursor: pointer; border-radius: 5px;">🖨️ Print / Save as PDF</button>
        <a href="{{ route('orders.show', $order) }}" style="margin-left: 10px; text-decoration: none; color: #333;">&larr; Back to Order</a>
    </div>

    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <h1>Food Court</h1>
                            </td>
                            <td>
                                Invoice #: {{ $order->id }}<br>
                                Created: {{ $order->created_at->format('F d, Y') }}<br>
                                Status: {{ ucfirst($order->status) }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                Food Court Inc.<br>
                                123 Food Street<br>
                                New Delhi, India 110001
                            </td>
                            <td>
                                {{ $order->user->name }}<br>
                                {{ $order->user->email }}<br>
                                {{ $order->shipping_address }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="heading">
                <td>Item</td>
                <td>Price</td>
            </tr>
            @foreach($order->items as $item)
            <tr class="item">
                <td>
                    {{ $item->food->name }} <small>(x{{ $item->quantity }})</small>
                </td>
                <td>
                    ₹{{ number_format($item->price * $item->quantity, 2) }}
                </td>
            </tr>
            @endforeach
            <tr class="total">
                <td></td>
                <td>
                    Total: ₹{{ number_format($order->total_price, 2) }}
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
