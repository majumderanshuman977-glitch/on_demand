@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Service')

@section('content')

    <h4 class="py-3 mb-4">Edit Service</h4>

    <a href="{{ route('admin.services.index') }}" class="btn btn-secondary mb-3">← Back</a>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mt-2 mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">

            <form action="{{ route('admin.services.update', $service->id) }}" method="POST">
                @csrf


                {{-- Title --}}
                <div class="form-floating form-floating-outline mb-3">
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title', $service->title) }}" required>
                    <label>Service Title</label>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Sub Category Item --}}
                <div class="form-floating form-floating-outline mb-3">
                    <select name="sub_category_item_id"
                        class="form-select @error('sub_category_item_id') is-invalid @enderror">

                        <option value="">Select Sub Category Item</option>

                        @foreach ($items as $item)
                            <option value="{{ $item->id }}"
                                {{ old('sub_category_item_id', $service->sub_category_item_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                    <label>Sub Category Item</label>
                    @error('sub_category_item_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="form-floating form-floating-outline mb-3">
                    <textarea name="description" class="form-control" style="height: 120px;">{{ old('description', $service->description) }}</textarea>
                    <label>Description</label>
                </div>

                {{-- Price --}}
                <div class="form-floating form-floating-outline mb-3">
                    <input type="number" step="0.01" name="price"
                        class="form-control @error('price') is-invalid @enderror"
                        value="{{ old('price', $service->price) }}">
                    <label>Price</label>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Offer Price --}}
                <div class="form-floating form-floating-outline mb-3">
                    <input type="number" step="0.01" name="offer_price"
                        class="form-control @error('offer_price') is-invalid @enderror"
                        value="{{ old('offer_price', $service->offer_price) }}">
                    <label>Offer Price</label>
                    @error('offer_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Duration --}}
                <div class="form-floating form-floating-outline mb-3">
                    <input type="number" step="0.1" name="duration"
                        class="form-control @error('duration') is-invalid @enderror"
                        value="{{ old('duration', $service->duration) }}">
                    <label>Duration (in hours)</label>
                    @error('duration')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Includes --}}
                <div class="mb-3">
                    <label class="form-label">Includes (add multiple)</label>

                    <div id="includes-wrapper">
                        @php
                            $includes = old('includes', $service->includes ?? []);
                        @endphp

                        @foreach ($includes as $inc)
                            <div class="input-group mb-2">
                                <input type="text" name="includes[]" class="form-control" value="{{ $inc }}">
                                <button type="button" class="btn btn-danger remove-include">X</button>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-success btn-sm" id="add-include">+ Add Include</button>
                </div>

                <button class="btn btn-primary">Update Service</button>

            </form>

        </div>
    </div>

    {{-- JS for adding/removing includes --}}
    <script>
        document.getElementById('add-include').addEventListener('click', function() {
            let wrapper = document.getElementById('includes-wrapper');
            let html = `
        <div class="input-group mb-2">
            <input type="text" name="includes[]" class="form-control">
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
    </script>

@endsection
