@extends('layouts/contentNavbarLayout')

@section('title', 'Add Service Parts Category')

@section('content')
    <h4 class="py-3 mb-4">Add Service Parts Category</h4>

    <a href="{{ route('admin.servicePartsCategories.index') }}" class="btn btn-secondary mb-3">← Back</a>

    <div class="card p-3">
        <form action="{{ route('admin.servicePartsCategories.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Category Name *</label>
                <input type="text" name="name" class="form-control" required>
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Description (optional)</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-success">Create Category</button>
        </form>
    </div>
@endsection
