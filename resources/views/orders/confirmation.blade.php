@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Order Confirmation</h4>
            </div>
            <div class="card-body text-center">
                <i class="fas fa-check-circle text-success" style="font-size: 64px;"></i>
                <h2 class="mt-3">Thank You for Your Purchase!</h2>
                <p>Your order has been placed successfully.</p>
                
                <div class="alert alert-info">
                    <strong>Order #{{ $order->id }}</strong><br>
                    Total Amount: ${{ number_format($order->total_amount, 2) }}<br>
                    Payment Method: {{ strtoupper($order->payment_method) }}<br>
                    Payment Status: {{ ucfirst($order->payment_status) }}<br>
                    Order Status: {{ ucfirst($order->order_status) }}
                </div>
                
                <h5>Order Details</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Beat</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->beat->title }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <a href="{{ route('beats.index') }}" class="btn btn-primary">Continue Shopping</a>
                <a href="{{ route('orders.history') }}" class="btn btn-secondary">View My Orders</a>
            </div>
        </div>
    </div>
</div>
@endsection