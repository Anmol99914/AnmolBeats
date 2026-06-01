@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Manage Orders</h1>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total Amount</th>
                        <th>Payment Method</th>
                        <th>Payment Status</th>
                        <th>Order Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->user->name }}<br><small>{{ $order->user->email }}</small></td>
                        <td>${{ number_format($order->total_amount, 2) }}</td>
                        <td>
                            {{ strtoupper($order->payment_method) }}
                            @if($order->payment_method === 'cod' && $order->payment_status === 'pending')
                                <span class="badge bg-warning text-dark ms-1">Awaiting Approval</span>
                            @endif
                        </td>
                        <td>
                            @if($order->payment_status == 'paid')
                                <span class="badge bg-success">✓ Paid</span>
                            @elseif($order->payment_status == 'refunded')
                                <span class="badge bg-warning text-dark">↺ Refunded</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td>
                            @if($order->order_status == 'confirmed')
                                <span class="badge bg-success">Confirmed</span>
                            @elseif($order->order_status == 'cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">View</a>
                            
                            @if($order->order_status != 'cancelled')
                                @if($order->payment_method === 'cod' && $order->order_status === 'pending')
                                    <form action="{{ route('admin.orders.update-status', [$order, 'confirmed']) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve this COD order? This will mark payment as paid.')">
                                            Approve COD
                                        </button>
                                    </form>
                                @elseif($order->order_status !== 'confirmed')
                                    <form action="{{ route('admin.orders.update-status', [$order, 'confirmed']) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success">Confirm</button>
                                    </form>
                                @endif
                                
                                <form action="{{ route('admin.orders.update-status', [$order, 'cancelled']) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Cancel this order?')">Cancel</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No orders found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection