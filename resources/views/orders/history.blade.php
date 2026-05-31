@extends('layouts.app')

@section('content')
<h1>My Orders</h1>

@if($orders->isEmpty())
    <div class="alert alert-info">
        You haven't placed any orders yet. <a href="{{ route('beats.index') }}">Start shopping</a>
    </div>
@else
    <div class="table-responsive">
        <table class="table table-bordered">
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
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                    <td>${{ number_format($order->total_amount, 2) }}</td>
                    <td>{{ strtoupper($order->payment_method) }}</td>
                    <td>
                        <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-{{ $order->order_status == 'confirmed' ? 'success' : ($order->order_status == 'cancelled' ? 'danger' : 'warning') }}">
                            {{ ucfirst($order->order_status) }}
                        </span>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#orderModal{{ $order->id }}">
                            View Details
                        </button>
                    </td>
                </tr>
                
                <!-- Order Details Modal -->
                <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Order #{{ $order->id }} Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <h6>Items:</h6>
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Beat</th>
                                            <th>Qty</th>
                                            <th>Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                        <tr>
                                            <td>{{ $item->beat->title }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>${{ number_format($item->price, 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection