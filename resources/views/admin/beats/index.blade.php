@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Manage Beats</h1>
    <a href="{{ route('admin.beats.create') }}" class="btn btn-primary">Add Beat</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($beats as $beat)
                    <tr>
                        <td>{{ $beat->id }}</td>
                        <td>
                            <img src="{{ Storage::url($beat->image) }}" width="50" height="50" class="rounded">
                        </td>
                        <td>{{ $beat->title }}</td>
                        <td>{{ $beat->category->name }}</td>
                        <td>${{ number_format($beat->price, 2) }}</td>
                        <td>
                            <a href="{{ route('admin.beats.edit', $beat) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.beats.destroy', $beat) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection