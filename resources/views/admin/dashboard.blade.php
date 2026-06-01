@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Total Users</h5>
                <h2 class="mb-0">{{ $totalUsers }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Total Beats</h5>
                <h2 class="mb-0">{{ $totalBeats }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">Total Orders</h5>
                <h2 class="mb-0">{{ $totalOrders }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">Gross Revenue</h5>
                <h2 class="mb-0">${{ number_format($totalRevenue, 2) }}</h2>
                <small class="text-white-50">From paid orders</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <div class="card bg-dark border-secondary">
            <div class="card-header border-secondary">
                <h5 class="mb-0 text-white">Revenue Breakdown</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Gross Revenue (Paid Orders):</span>
                    <span class="text-success">${{ number_format($totalRevenue, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Refunded Amount:</span>
                    <span class="text-danger">-${{ number_format($refundedAmount, 2) }}</span>
                </div>
                <hr class="border-secondary">
                <div class="d-flex justify-content-between">
                    <strong class="text-white">Net Revenue:</strong>
                    <strong class="text-success fs-4">${{ number_format($netRevenue - $refundedAmount, 2) }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="card bg-dark border-secondary">
            <div class="card-header border-secondary">
                <h5 class="mb-0 text-white">Quick Actions</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.beats.create') }}" class="btn btn-primary mb-2 w-100">Add New Beat</a>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-secondary w-100">Add Category</a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card bg-dark border-secondary">
            <div class="card-header border-secondary">
                <h5 class="mb-0 text-white">Recent Orders</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-dark table-sm">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Payment Status</th>
                                <th>Order Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\Order::with('user')->latest()->limit(5)->get() as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>${{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    @if($order->payment_method)
                                    {{ strtoupper($order->payment_method) }}
                                    @else
                                    <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($order->payment_status == 'paid')
                                    <span class="badge bg-success">Paid</span>
                                    @elseif($order->payment_status == 'refunded')
                                    <span class="badge bg-warning text-dark">Refunded</span>
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
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection