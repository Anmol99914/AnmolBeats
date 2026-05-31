@extends('layouts.app')

@section('content')
<h1>Checkout</h1>

<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Payment Method</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                    @csrf
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" required>
                            <label class="form-check-label" for="cod">
                                <i class="fas fa-money-bill"></i> Cash on Delivery (COD)
                            </label>
                        </div>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="radio" name="payment_method" id="esewa" value="esewa">
                            <label class="form-check-label" for="esewa">
                                <i class="fas fa-wallet"></i> eSewa (Simulated)
                            </label>
                        </div>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="radio" name="payment_method" id="card" value="card">
                            <label class="form-check-label" for="card">
                                <i class="fas fa-credit-card"></i> Card Payment (Simulated)
                            </label>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> This is a simulated payment system. Your payment will be marked as "paid" immediately.
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100">Pay Now</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Order Summary</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6>Items:</h6>
                    @foreach($cart as $item)
                        <div class="d-flex justify-content-between">
                            <span>{{ $item['title'] }} x {{ $item['quantity'] }}</span>
                            <span>${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                        </div>
                    @endforeach
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <strong>Total:</strong>
                    <strong class="text-primary">${{ number_format($total, 2) }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection