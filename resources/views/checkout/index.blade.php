@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Checkout</h1>

    <div class="row">
        <div class="col-md-7">
            <div class="card bg-dark border-secondary">
                <div class="card-header border-secondary">
                    <h5 class="mb-0 text-white">Select Payment Method</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                        @csrf
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" required>
                                <label class="form-check-label text-white" for="cod">
                                    <i class="fas fa-money-bill"></i> Cash on Delivery (COD)
                                </label>
                                <p class="text-muted small ms-4">Pay when you receive the beats. Payment pending until delivery.</p>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="stripe" value="stripe">
                                <label class="form-check-label text-white" for="stripe">
                                    <i class="fab fa-stripe"></i> Credit / Debit Card (Stripe)
                                </label>
                                <p class="text-muted small ms-4">Secure payment via Stripe. Instant access to beats.</p>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Payment Options:</strong>
                            <ul class="mb-0 mt-2">
                                <li><strong>COD</strong> - Pay on delivery. Admin will confirm payment.</li>
                                <li><strong>Stripe</strong> - Pay instantly with credit/debit card.</li>
                            </ul>
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100 mt-3" id="submitBtn">Place Order</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-5">
            <div class="card bg-dark border-secondary">
                <div class="card-header border-secondary">
                    <h5 class="mb-0 text-white">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-white">Items:</h6>
                        @foreach($cartItems as $item)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">{{ $item->beat->title }}</span>
                                <span class="text-white">${{ number_format($item->beat->price, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                    <hr class="border-secondary">
                    <div class="d-flex justify-content-between">
                        <strong class="text-white">Total:</strong>
                        <strong class="text-success fs-4">${{ number_format($total, 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var form = document.getElementById('checkout-form');
    var submitBtn = document.getElementById('submitBtn');
    
    form.addEventListener('submit', function(event) {
        var selected = document.querySelector('input[name="payment_method"]:checked');
        
        if (selected && selected.value === 'stripe') {
            event.preventDefault();
            window.location.href = "{{ route('checkout.stripe') }}";
        }
    });
</script>
@endsection