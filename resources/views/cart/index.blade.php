@extends('layouts.app')

@section('content')
<h1>Shopping Cart</h1>

@if($cartItems->isEmpty())
    <div class="alert alert-info">
        Your cart is empty. <a href="{{ route('beats.index') }}">Continue shopping</a>
    </div>
@else
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    @foreach($cartItems as $item)
                        <div class="row mb-3 cart-item" data-id="{{ $item->beat->id }}">
                            <div class="col-md-3">
                                @if(Storage::exists($item->beat->image))
                                    <img src="{{ Storage::url($item->beat->image) }}" class="img-fluid" alt="{{ $item->beat->title }}">
                                @else
                                    <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 100px;">
                                        <i class="fas fa-music fa-2x text-white"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-5">
                                <h5>{{ $item->beat->title }}</h5>
                                <p>Price: ${{ number_format($item->beat->price, 2) }}</p>
                            </div>
                            <div class="col-md-2">
                                <input type="number" class="form-control quantity-input" 
                                       value="{{ $item->quantity }}" min="1" data-id="{{ $item->beat->id }}">
                            </div>
                            <div class="col-md-2">
                                <p><strong>${{ number_format($item->beat->price * $item->quantity, 2) }}</strong></p>
                                <form action="{{ route('cart.remove', $item->beat->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                </form>
                            </div>
                        </div>
                        @if(!$loop->last)
                            <hr>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td>Subtotal:</td>
                            <td class="text-end">${{ number_format($total, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Tax (0%):</td>
                            <td class="text-end">$0.00</td>
                        </tr>
                        <tr class="table-primary">
                            <th>Total:</th>
                            <th class="text-end">${{ number_format($total, 2) }}</th>
                        </tr>
                    </table>
                    <a href="{{ route('checkout.index') }}" class="btn btn-success w-100">Proceed to Checkout</a>
                    <a href="{{ route('beats.index') }}" class="btn btn-secondary w-100 mt-2">Continue Shopping</a>
                </div>
            </div>
        </div>
    </div>
@endif

@push('scripts')
<script>
document.querySelectorAll('.quantity-input').forEach(input => {
    input.addEventListener('change', function() {
        const id = this.dataset.id;
        const quantity = this.value;
        
        fetch(`/cart/update/${id}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ quantity: quantity })
        }).then(() => {
            location.reload();
        });
    });
});
</script>
@endpush
@endsection