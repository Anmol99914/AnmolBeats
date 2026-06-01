@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6">
        <img src="{{ Storage::url($beat->image) }}" class="img-fluid rounded" alt="{{ $beat->title }}">
    </div>
    <div class="col-md-6">
        <h1>{{ $beat->title }}</h1>
        <p class="text-muted">Category: {{ $beat->category->name }}</p>
        <p class="lead">{{ $beat->description }}</p>
        <h3 class="text-primary">${{ number_format($beat->price, 2) }}</h3>

        <div class="mt-4">
            <h5>Audio Preview</h5>
            <audio controls controlsList="nodownload" class="w-100">
                <source src="{{ Storage::url($beat->audio_file) }}" type="audio/mpeg">
                Your browser does not support the audio element.
            </audio>
        </div>

        @auth
        <form action="{{ route('cart.add', $beat) }}" method="POST" class="mt-4">
            @csrf
            <button type="submit" class="btn btn-primary btn-lg w-100">
                <i class="fas fa-cart-plus"></i> Add to Cart
            </button>
        </form>
        @else
        <div class="alert alert-info mt-4">
            Please <a href="{{ route('login') }}">login</a> to purchase this beat.
        </div>
        @endauth
    </div>
</div>
@endsection