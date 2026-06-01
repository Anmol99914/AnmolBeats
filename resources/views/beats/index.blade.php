@extends('layouts.app')

@section('content')
<div class="hero" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 80px 0; margin-bottom: 50px;">
    <div class="container text-center">
        <h1 class="display-4 fw-bold">Find The Perfect Music</h1>
        <p class="lead">Discover unique beats for your next project</p>
    </div>
</div>

<div class="container">
    @if(request('search'))
    <div class="alert alert-info mb-4">
        <i class="fas fa-search"></i> Search results for: <strong>"{{ request('search') }}"</strong>
        <a href="{{ route('beats.index') }}" class="float-end text-decoration-none">Clear search</a>
    </div>
    <div class="beats-section" id="beats-section">
    <div class="row">
        <!-- Your beats grid here -->
    </div>
</div>
    @endif

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="mb-0">Filter by Category</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush bg-transparent">
                        <a href="{{ route('beats.index') }}" class="list-group-item list-group-item-action bg-transparent text-white">All Categories</a>
                        @foreach($categories as $category)
                        <a href="{{ route('beats.index', ['category' => $category->id]) }}"
                            class="list-group-item list-group-item-action bg-transparent text-white">
                            {{ $category->name }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <h2 class="mb-4">Latest Beats</h2>
            <div class="row">
                @forelse($beats as $beat)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="audio-preview" style="background: #1a1a1a; padding: 10px;">
                            <audio controls controlsList="nodownload" class="w-100">
                                <source src="{{ Storage::url($beat->audio_file) }}" type="audio/mpeg">
                            </audio>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">{{ $beat->title }}</h5>
                                <small class="text-muted">{{ $beat->category->name }}</small>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="text-primary">${{ number_format($beat->price, 2) }}</strong>
                                <form action="{{ route('cart.add', $beat) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        @auth Add to Cart @else Add to Cart @endauth
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info">No beats found. <a href="{{ route('admin.beats.create') }}">Add beats</a> to get started.</div>
                </div>
                @endforelse
            </div>
            <div class="d-flex justify-content-center">
                {{ $beats->links() }}
            </div>
        </div>
    </div>
</div>
@endsection