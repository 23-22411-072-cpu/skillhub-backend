<x-mail::message>
# Order Status Updated

Hello {{ $userRole }},

Your Order #{{ $order->id }} has been updated.

**New Status:** {{ strtoupper($order->status) }}

**Order Details:**
* Service: {{ $order->service->service_name ?? 'Service Not Found' }}
* Date/Time: {{ \Carbon\Carbon::parse($order->scheduled_at)->format('Y-m-d') }} at {{ \Carbon\Carbon::parse($order->scheduled_at)->format('h:i A') }}
* Total Amount: PKR {{ number_format($order->total_price, 2) }} 
{{-- NOTE: Yahan total_amount ki jagah 'total_price' use kiya gaya hai --}}

@if ($order->status == 'completed')
We hope you were satisfied with the service provided!
@endif

<x-mail::button :url="url('/orders/' . $order->id)">
View Order Details
</x-mail::button>

Thanks,<br>
The SkillHub Team
</x-mail::message>