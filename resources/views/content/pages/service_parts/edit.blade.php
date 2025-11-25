@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Service Part')

@section('content')

    <h4 class="py-3 mb-4">Edit Service Part</h4>

    <a href="{{ route('admin.serviceParts.index') }}" class="btn btn-secondary mb-3">← Back</a>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('admin.serviceParts.update', $part->id) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Select Category</label>
                    <select name="category_id" class="form-select" required>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $cat->id == $part->category_id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-floating form-floating-outline mb-3">
                    <input type="text" name="part_name" class="form-control" value="{{ $part->part_name }}" required>
                    <label>Part Name</label>
                </div>

                <div class="form-floating form-floating-outline mb-3">
                    <input type="number" step="0.01" name="base_cost" class="form-control"
                        value="{{ $part->base_cost }}">
                    <label>Base Cost</label>
                </div>

                <button class="btn btn-primary">Update</button>

            </form>

        </div>
    </div>

@endsection
