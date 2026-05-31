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
                <h5 class="card-title">Total Revenue</h5>
                <h2 class="mb-0">${{ number_format($totalRevenue, 2) }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Quick Actions
            </div>
            <div class="card-body">
                <a href="{{ route('admin.beats.create') }}" class="btn btn-primary">Add New Beat</a>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-secondary">Add Category</a>
            </div>
        </div>
    </div>
</div>
@endsection