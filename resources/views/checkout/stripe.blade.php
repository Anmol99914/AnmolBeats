@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Stripe Payment</h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="text-center mb-4">
                        <i class="fab fa-stripe fa-3x text-primary mb-3"></i>
                        <h5>You will be redirected to Stripe's secure payment page</h5>
                        <p class="text-muted">Total amount: <strong>${{ number_format($total, 2) }}</strong></p>
                    </div>

                    <form action="{{ route('checkout.stripe.process') }}" method="POST" id="payment-form">
                        @csrf
                        <input type="hidden" name="amount" value="{{ $total * 100 }}">
                        <button type="submit" class="btn btn-primary w-100" id="submit-btn">
                            Proceed to Stripe Checkout →
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection