@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Sub Category Item')

@section('content')
    <div class="container">
        <h2 class="mb-4">Edit Sub Category Item</h2>

        <a href="{{ route('admin.subcategory-items.index') }}" class="btn btn-secondary mb-3">← Back</a>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Fix the following errors:</strong>
                <ul class="mt-2">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">

                <form action="{{ route('admin.subcategory-items.update', $item->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- CATEGORY --}}
                    <div class="mb-3">
                        <label class="form-label">Select Category</label>
                        <select name="category_id" class="form-control">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $item->category_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- TYPE --}}
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <input type="text" name="type" class="form-control" value="{{ $item->type }}" required>
                    </div>

                    {{-- TYPE IMAGE UPLOAD --}}
                    <div class="mb-3">
                        <label class="form-label">Category Type Image</label>
                        <input type="file" name="category_type_image" class="form-control" accept="image/*"
                            id="type_img">
                    </div>

                    {{-- PREVIEW OLD TYPE IMAGE --}}
                    @if ($item->category_type_image)
                        <div class="mb-3">
                            <label class="form-label">Current Type Image:</label><br>
                            <img id="typePreview" src="{{ asset('storage/' . $item->category_type_image) }}" width="120"
                                height="120" style="object-fit: cover; border-radius: 10px;">
                        </div>
                    @else
                        <img id="typePreview" style="display:none;width:120px;height:120px;">
                    @endif

                    {{-- ITEM --}}
                    <div class="mb-3">
                        <label class="form-label">Item Name</label>
                        <input type="text" name="item" class="form-control" value="{{ $item->item }}">
                    </div>

                    {{-- ITEM IMAGE --}}
                    <div class="mb-3">
                        <label class="form-label">Item Image</label>
                        <input type="file" name="item_image" class="form-control" accept="image/*" id="item_img">
                    </div>

                    {{-- EXISTING ITEM IMAGE --}}
                    @if ($item->item_image)
                        <div class="mb-3">
                            <label class="form-label">Current Item Image:</label><br>
                            <img id="itemPreview" src="{{ asset('storage/' . $item->item_image) }}" width="120"
                                height="120" style="object-fit: cover; border-radius: 10px;">
                        </div>
                    @else
                        <img id="itemPreview" style="display:none;width:120px;height:120px;">
                    @endif

                    <button class="btn btn-primary">Update</button>

                </form>

            </div>
        </div>
    </div>
@endsection


@section('page-script')
    <script>
        // IMAGE PREVIEW FOR TYPE IMAGE
        document.getElementById('type_img').addEventListener('change', function(e) {
            let preview = document.getElementById('typePreview');
            preview.style.display = "block";
            preview.src = URL.createObjectURL(e.target.files[0]);
        });

        // IMAGE PREVIEW FOR ITEM IMAGE
        document.getElementById('item_img').addEventListener('change', function(e) {
            let preview = document.getElementById('itemPreview');
            preview.style.display = "block";
            preview.src = URL.createObjectURL(e.target.files[0]);
        });
    </script>
@endsection
