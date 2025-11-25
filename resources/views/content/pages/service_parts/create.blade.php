@extends('layouts/contentNavbarLayout')

@section('title', 'Add Service Part')

@section('content')

    <h4 class="py-3 mb-4">Add Service Part</h4>

    <a href="{{ route('admin.serviceParts.index') }}" class="btn btn-secondary mb-3">← Back</a>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('admin.serviceParts.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Select Category</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">Choose category</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-floating form-floating-outline mb-3">
                    <input type="text" name="part_name" class="form-control" required>
                    <label>Part Name</label>
                </div>

                <div class="form-floating form-floating-outline mb-3">
                    <input type="number" step="0.01" name="base_cost" class="form-control">
                    <label>Base Cost</label>
                </div>

                <button class="btn btn-primary">Save</button>

            </form>

        </div>
    </div>

@endsection
