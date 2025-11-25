@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Category')

@section('content')

    <h4 class="py-3 mb-4">Edit Category</h4>

    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary mb-3">← Back</a>

    {{-- Global Validation Error Messages --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>There were some problems with your input:</strong>
            <ul class="mt-2 mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">

            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Category Name --}}
                <div class="form-floating form-floating-outline mb-3">
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $category->name) }}" required>
                    <label>Category Name</label>

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="form-floating form-floating-outline mb-3">
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" style="height: 120px;">{{ old('description', $category->description) }}</textarea>
                    <label>Description</label>

                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Image Upload --}}
                <div class="mb-3">
                    <label class="form-label">Category Image</label>
                    <input type="file" name="category_image" id="imageInput"
                        class="form-control @error('category_image') is-invalid @enderror" accept="image/*">

                    @error('category_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Existing Preview --}}
                <div class="mb-3">
                    <label class="form-label">Image Preview:</label><br>

                    <img id="previewImage"
                        src="{{ $category->category_image ? asset('storage/' . $category->category_image) : '' }}"
                        style="width: 120px; height: 120px; object-fit: cover; border-radius: 10px;
                                {{ $category->category_image ? '' : 'display:none;' }}">
                </div>

                <button class="btn btn-primary">Update</button>
            </form>

        </div>
    </div>

@endsection


@section('page-script')
    <script>
        // Live preview when new image selected
        document.getElementById('imageInput').addEventListener('change', function(e) {
            const preview = document.getElementById('previewImage');
            const file = e.target.files[0];

            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.style.display = "block";
            }
        });
    </script>
@endsection
