@extends('layouts/contentNavbarLayout')

@section('title', 'Add Service')

@section('content')

    <h4 class="py-3 mb-4">Add Service</h4>

    <a href="{{ route('admin.services.index') }}" class="btn btn-secondary mb-3">← Back</a>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">

            <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Title --}}
                <div class="form-floating form-floating-outline mb-3">
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                    <label>Service Title</label>
                </div>

                {{-- Sub Category --}}
                <div class="form-floating form-floating-outline mb-3">
                    <select name="sub_category_item_id" class="form-select">
                        <option value="">Select Sub Category Item</option>

                        @foreach ($items as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->category->name }} -> {{ $item->item }}
                            </option>
                        @endforeach
                    </select>
                    <label>Sub Category</label>
                </div>

                {{-- Description --}}
                <div class="form-floating form-floating-outline mb-3">
                    <textarea name="description" class="form-control" style="height: 100px;">{{ old('description') }}</textarea>
                    <label>Description</label>
                </div>

                {{-- Price, Offer Price, Duration --}}
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="form-floating form-floating-outline">
                            <input type="number" step="0.01" name="price" class="form-control"
                                value="{{ old('price') }}">
                            <label>Price</label>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="form-floating form-floating-outline">
                            <input type="number" step="0.01" name="offer_price" class="form-control"
                                value="{{ old('offer_price') }}">
                            <label>Offer Price</label>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="form-floating form-floating-outline">
                            <input type="number" step="0.01" name="duration" class="form-control"
                                value="{{ old('duration') }}">
                            <label>Duration (hrs)</label>
                        </div>
                    </div>
                </div>

                {{-- Service Image with Preview --}}
                <div class="mb-3">
                    <label class="form-label">Service Image</label>
                    <input type="file" name="services_image" class="form-control" accept="image/*"
                        id="services_image_input">
                    <img id="services_image_preview" src="" alt="Preview" class="img-fluid mt-2"
                        style="max-height: 200px; display: none;">
                </div>

                {{-- Includes --}}
                <div class="mb-3">
                    <label class="form-label">Includes (add multiple)</label>

                    <div id="includes-wrapper">
                        @php
                            $includes = old('includes', []);
                        @endphp

                        @if (!empty($includes))
                            @foreach ($includes as $inc)
                                <div class="input-group mb-2">
                                    <input type="text" name="includes[]" class="form-control"
                                        value="{{ $inc }}">
                                    <button type="button" class="btn btn-danger remove-include">X</button>
                                </div>
                            @endforeach
                        @else
                            <div class="input-group mb-2">
                                <input type="text" name="includes[]" class="form-control"
                                    placeholder="Enter include item">
                                <button type="button" class="btn btn-danger remove-include">X</button>
                            </div>
                        @endif
                    </div>

                    <button type="button" class="btn btn-success btn-sm" id="add-include">+ Add Include</button>
                </div>

                <button class="btn btn-primary">Save</button>
            </form>

        </div>
    </div>

    {{-- JS --}}
    <script>
        // Add/Remove includes
        document.getElementById('add-include').addEventListener('click', function() {
            let wrapper = document.getElementById('includes-wrapper');
            let html = `
            <div class="input-group mb-2">
                <input type="text" name="includes[]" class="form-control" placeholder="Enter include item">
                <button type="button" class="btn btn-danger remove-include">X</button>
            </div>
        `;
            wrapper.insertAdjacentHTML('beforeend', html);
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-include')) {
                e.target.parentElement.remove();
            }
        });

        // Image preview
        const imageInput = document.getElementById('services_image_input');
        const imagePreview = document.getElementById('services_image_preview');

        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = '';
                imagePreview.style.display = 'none';
            }
        });
    </script>

@endsection
