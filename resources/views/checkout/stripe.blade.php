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

                    <form id="payment-form">
                        @csrf
                        
                        <input type="hidden" id="stripe-key" value="{{ config('services.stripe.key') }}">
                        <input type="hidden" id="total-amount" value="{{ $total }}">
                        <input type="hidden" id="process-url" value="{{ route('checkout.stripe.process') }}">
                        <input type="hidden" id="csrf-token" value="{{ csrf_token() }}">
                        
                        <div class="form-group mb-3">
                            <label>Cardholder Name</label>
                            <input type="text" id="cardholder_name" class="form-control" required>
                        </div>

                        <div id="card-element" class="form-control" style="padding: 12px; min-height: 40px;"></div>
                        <div id="card-errors" class="text-danger mt-2"></div>

                        <button type="submit" class="btn btn-primary w-100 mt-3" id="submit-btn">
                            Pay ${{ number_format($total, 2) }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script src="{{ asset('js/stripe-checkout.js') }}"></script>
@endsection