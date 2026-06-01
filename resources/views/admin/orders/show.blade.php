@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Order #{{ $order->id }} Details</h1>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Back to Orders</a>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Order Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Order ID:</th>
                        <td>#{{ $order->id }}</td>
                    </tr>
                    <tr>
                        <th>Customer:</th>
                        <td>{{ $order->user->name }} ({{ $order->user->email }})</td>
                    </tr>
                    <tr>
                        <th>Total Amount:</th>
                        <td>${{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Payment Method:</th>
                        <td>
                            @if($order->payment_method)
                            {{ strtoupper($order->payment_method) }}
                            @else
                            <span class="text-muted">Not recorded</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Payment Status:</th>
                        <td>
                            @if($order->payment_status == 'paid')
                            <span class="badge bg-success">✓ Paid</span>
                            @elseif($order->payment_status == 'refunded')
                            <span class="badge bg-warning text-dark">↺ Refunded</span>
                            @else
                            <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Order Status:</th>
                        <td>
                            @if($order->order_status == 'confirmed')
                            <span class="badge bg-success">Confirmed</span>
                            @elseif($order->order_status == 'cancelled')
                            <span class="badge bg-danger">Cancelled</span>
                            @else
                            <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Date:</th>
                        <td>{{ $order->created_at->format('F d, Y H:i:s') }}</td>
                    </tr>
                    @if($order->stripe_charge_id)
                    <tr>
                        <th>Stripe Charge ID:</th>
                        <td><code>{{ $order->stripe_charge_id }}</code></td>
                    </tr>
                    @endif
                </table>

                <!-- COD Approval Button -->
                @if($order->payment_method === 'cod' && $order->order_status === 'pending')
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-clock"></i> This is a COD order awaiting payment confirmation.
                    <form action="{{ route('admin.orders.update-status', [$order, 'confirmed']) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-success ms-3">Approve & Mark as Paid</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Order Items</h5>
            </div>
            <div class="card-body">
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
                    <tfoot>
                        <tr class="table-active">
                            <td colspan="3" class="text-end fw-bold">Total:</td>
                            <td class="text-success fw-bold">${{ number_format($order->total_amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection