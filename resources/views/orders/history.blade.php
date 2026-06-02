@extends('layouts.app')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
<div class="container">
    <h1 class="mb-4">My Orders</h1>

    @if($orders->isEmpty())
        <div class="alert alert-info">
            You haven't placed any orders yet. <a href="{{ route('beats.index') }}">Start shopping</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-dark table-hover">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Total Amount</th>
                        <th>Payment Method</th>
                        <th>Payment Status</th>
                        <th>Order Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td><strong>#{{ $order->id }}</strong></td>
                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="text-success fw-bold">${{ number_format($order->total_amount, 2) }}</td>
                        <td>
                            @if($order->payment_method)
                                {{ strtoupper($order->payment_method) }}
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($order->payment_status == 'paid')
                                <span class="badge bg-success">✓ Paid</span>
                            @elseif($order->payment_status == 'refunded')
                                <span class="badge bg-warning text-dark">↺ Refunded</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>
                        <td>
                            @if($order->order_status == 'confirmed')
                                <span class="badge bg-success">Confirmed</span>
                            @elseif($order->order_status == 'cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#orderModal{{ $order->id }}">
                                <i class="fas fa-eye"></i> View Details
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Order Details Modals -->
        @foreach($orders as $order)
        <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-dark text-white">
                    <div class="modal-header border-secondary">
                        <h5 class="modal-title">Order #{{ $order->id }} Details</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <strong>Order Date:</strong> {{ $order->created_at->format('F d, Y H:i') }}
                        </div>
                        <div class="mb-3">
                            <strong>Payment Method:</strong> 
                            @if($order->payment_method)
                                <span class="badge bg-info">{{ strtoupper($order->payment_method) }}</span>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <strong>Payment Status:</strong>
                            @if($order->payment_status == 'paid')
                                <span class="badge bg-success">✓ Paid</span>
                            @elseif($order->payment_status == 'refunded')
                                <span class="badge bg-warning text-dark">↺ Refunded</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <strong>Order Status:</strong>
                            @if($order->order_status == 'confirmed')
                                <span class="badge bg-success">Confirmed</span>
                            @elseif($order->order_status == 'cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </div>
                        <hr class="border-secondary">
                        <h6 class="mb-3">Items Purchased:</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-dark">
                                <thead>
                                    <tr>
                                        <th>Beat</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->beat->title }}</td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-active">
                                        <td class="text-end fw-bold">Total:</td>
                                        <td class="text-success fw-bold">${{ number_format($order->total_amount, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection