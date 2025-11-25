@extends('layouts/contentNavbarLayout')

@section('title', 'Add Category')

@section('content')

    <h4 class="py-3 mb-4">Add Category</h4>

    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary mb-3">← Back</a>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Category Name --}}
                <div class="form-floating form-floating-outline mb-3">
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" required>
                    <label>Category Name</label>

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="form-floating form-floating-outline mb-3">
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" style="height: 120px;">{{ old('description') }}</textarea>
                    <label>Description</label>

                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Category Image --}}
                <div class="mb-3">
                    <label class="form-label">Category Image</label>
                    <input type="file" name="category_image"
                        class="form-control @error('category_image') is-invalid @enderror" accept="image/*">

                    @error('category_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button class="btn btn-primary">Save</button>
            </form>

        </div>
    </div>

@endsection
