@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Success Card -->
            <div class="card bg-dark border-success mb-4">
                <div class="card-body text-center py-4">
                    <i class="fas fa-check-circle text-success" style="font-size: 64px;"></i>
                    <h2 class="mt-3 text-white">Thank You for Your Purchase!</h2>
                    <p class="text-muted">Your order has been placed successfully.</p>
                </div>
            </div>

            <!-- Order Details Card -->
            <div class="card bg-dark border-secondary">
                <div class="card-header bg-secondary bg-opacity-25 border-secondary">
                    <h4 class="mb-0 text-white">Order Details</h4>
                </div>
                <div class="card-body">
                    <!-- Order Info Grid -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="bg-dark p-3 rounded border border-secondary">
                                <small class="text-muted d-block">Order Number</small>
                                <strong class="text-white fs-4">#{{ $order->id }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="bg-dark p-3 rounded border border-secondary">
                                <small class="text-muted d-block">Total Amount</small>
                                <strong class="text-success fs-4">${{ number_format($order->total_amount, 2) }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="bg-dark p-3 rounded border border-secondary">
                                <small class="text-muted d-block">Payment Method</small>
                                <span class="badge bg-info fs-6">{{ strtoupper($order->payment_method) }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="bg-dark p-3 rounded border border-secondary">
                                <small class="text-muted d-block">Payment Status</small>
                                @if($order->payment_status == 'paid')
                                    <span class="badge bg-success fs-6">✓ Paid</span>
                                @elseif($order->payment_status == 'refunded')
                                    <span class="badge bg-warning text-dark fs-6">↺ Refunded</span>
                                @else
                                    <span class="badge bg-warning fs-6">Pending</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="bg-dark p-3 rounded border border-secondary">
                                <small class="text-muted d-block">Order Status</small>
                                @if($order->order_status == 'confirmed')
                                    <span class="badge bg-success fs-6">Confirmed</span>
                                @elseif($order->order_status == 'cancelled')
                                    <span class="badge bg-danger fs-6">Cancelled</span>
                                @else
                                    <span class="badge bg-warning fs-6">Pending</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="bg-dark p-3 rounded border border-secondary">
                                <small class="text-muted d-block">Order Date</small>
                                <strong class="text-white">{{ $order->created_at->format('F d, Y H:i') }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items Table -->
                    <h5 class="text-white mb-3">Order Items</h5>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover">
                            <thead>
                                <tr class="border-secondary">
                                    <th class="text-muted">Beat</th>
                                    <th class="text-muted text-center">Quantity</th>
                                    <th class="text-muted text-end">Price</th>
                                    <th class="text-muted text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td class="text-white">
                                        <i class="fas fa-music text-info me-2"></i>
                                        {{ $item->beat->title }}
                                    </td>
                                    <td class="text-center text-white">x{{ $item->quantity }}</td>
                                    <td class="text-end text-white">${{ number_format($item->price, 2) }}</td>
                                    <td class="text-end text-success">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="border-secondary">
                                    <td colspan="3" class="text-end fw-bold text-white">Total:</td>
                                    <td class="text-end fw-bold text-success fs-5">${{ number_format($order->total_amount, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-3 mt-4">
                        <a href="{{ route('beats.index') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
                        </a>
                        <a href="{{ route('orders.history') }}" class="btn btn-outline-info">
                            <i class="fas fa-history me-2"></i>View My Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection