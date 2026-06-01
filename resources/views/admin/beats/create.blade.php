@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Add New Beat</h1>
    <a href="{{ route('admin.beats.index') }}" class="btn btn-secondary">Back to Beats</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.beats.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                       id="title" name="title" value="{{ old('title') }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select class="form-control @error('category_id') is-invalid @enderror" 
                        id="category_id" name="category_id" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="price" class="form-label">Price ($)</label>
                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                       id="price" name="price" value="{{ old('price') }}" required>
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="image" class="form-label">Cover Image</label>
                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                       id="image" name="image" accept="image/*" required>
                <small class="text-muted">Supported formats: JPG, PNG (Max 2MB)</small>
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="audio_file" class="form-label">Audio Preview (MP3)</label>
                <input type="file" class="form-control @error('audio_file') is-invalid @enderror" 
                       id="audio_file" name="audio_file" accept="audio/*" required>
                <small class="text-muted">Supported formats: MP3, WAV (Max 10MB)</small>
                @error('audio_file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Create Beat</button>
                <a href="{{ route('admin.beats.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection