@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Edit Beat</h1>
    <a href="{{ route('admin.beats.index') }}" class="btn btn-secondary">Back to Beats</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.beats.update', $beat) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                       id="title" name="title" value="{{ old('title', $beat->title) }}" required>
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
                        <option value="{{ $category->id }}" {{ old('category_id', $beat->category_id) == $category->id ? 'selected' : '' }}>
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
                          id="description" name="description" rows="4" required>{{ old('description', $beat->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="price" class="form-label">Price ($)</label>
                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                       id="price" name="price" value="{{ old('price', $beat->price) }}" required>
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="image" class="form-label">Cover Image</label>
                @if($beat->image && Storage::exists($beat->image))
                    <div class="mb-2">
                        <img src="{{ Storage::url($beat->image) }}" width="100" class="rounded">
                    </div>
                @endif
                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                       id="image" name="image" accept="image/*">
                <small class="text-muted">Leave empty to keep current image. Supported: JPG, PNG (Max 2MB)</small>
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="audio_file" class="form-label">Audio Preview (MP3)</label>
                @if($beat->audio_file && Storage::exists($beat->audio_file))
                    <div class="mb-2">
                        <audio controls>
                            <source src="{{ Storage::url($beat->audio_file) }}" type="audio/mpeg">
                        </audio>
                    </div>
                @endif
                <input type="file" class="form-control @error('audio_file') is-invalid @enderror" 
                       id="audio_file" name="audio_file" accept="audio/*">
                <small class="text-muted">Leave empty to keep current audio. Supported: MP3, WAV (Max 10MB)</small>
                @error('audio_file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Update Beat</button>
                <a href="{{ route('admin.beats.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection