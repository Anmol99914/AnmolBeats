@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Shopping Cart</h1>

    @if($cartItems->isEmpty())
        <div class="alert alert-info">
            Your cart is empty. <a href="{{ route('beats.index') }}">Continue shopping</a>
        </div>
    @else
        <div class="row">
            <div class="col-md-8">
                <div class="card bg-dark">
                    <div class="card-body">
                        @foreach($cartItems as $item)
                            <div class="row mb-3 cart-item align-items-center" data-id="{{ $item->beat->id }}">
                                <div class="col-md-2">
                                    @php
                                        $imagePath = $item->beat->image;
                                    @endphp
                                    @if($imagePath && Storage::disk('public')->exists($imagePath))
                                        <img src="{{ Storage::url($imagePath) }}" class="img-fluid rounded" alt="{{ $item->beat->title }}" style="height: 80px; width: 80px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary d-flex align-items-center justify-content-center rounded" style="height: 80px; width: 80px;">
                                            <i class="fas fa-music fa-2x text-white"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-5">
                                    <h5 class="text-white">{{ $item->beat->title }}</h5>
                                    <p class="text-muted mb-0">{{ $item->beat->category->name }}</p>
                                </div>
                                <div class="col-md-3">
                                    <p class="text-success fw-bold mb-0">${{ number_format($item->beat->price, 2) }}</p>
                                </div>
                                <div class="col-md-2 text-end">
                                    <form action="{{ route('cart.remove', $item->beat->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @if(!$loop->last)
                                <hr class="border-secondary">
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card bg-dark">
                    <div class="card-header bg-secondary bg-opacity-25">
                        <h5 class="mb-0 text-white">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal:</span>
                            <span class="text-white">${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tax (0%):</span>
                            <span class="text-white">$0.00</span>
                        </div>
                        <hr class="border-secondary">
                        <div class="d-flex justify-content-between mb-3">
                            <strong class="text-white">Total:</strong>
                            <strong class="text-success fs-5">${{ number_format($total, 2) }}</strong>
                        </div>
                        <a href="{{ route('checkout.index') }}" class="btn btn-success w-100 mb-2">Proceed to Checkout</a>
                        <a href="{{ route('beats.index') }}" class="btn btn-outline-secondary w-100">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection