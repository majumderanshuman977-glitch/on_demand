@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Category')

@section('content')

    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Categories /</span> Edit Category
    </h4>

    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary mb-3">
        ← Back to Categories
    </a>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">

                @csrf
                @method('PUT')

                {{-- Name --}}
                <div class="mb-3">
                    <label class="form-label">Category Name</label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}"
                        class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $category->description) }}</textarea>

                    @error('description')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Current Image --}}
                @if ($category->image)
                    <div class="mb-3">
                        <label class="form-label">Current Image</label><br>

                        <img src="{{ asset('storage/category_images/' . $category->image) }}" width="150"
                            class="img-thumbnail mb-2" alt="Category Image">

                    </div>
                @endif

                {{-- Upload New Image --}}
                <div class="mb-3">
                    <label class="form-label">Upload New Image</label>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">

                    @error('image')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    Update Category
                </button>

            </form>

        </div>
    </div>

@endsection
