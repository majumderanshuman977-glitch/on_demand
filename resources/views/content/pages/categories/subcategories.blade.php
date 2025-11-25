@extends('layouts/contentNavbarLayout')

@section('title', 'Sub Categories')

@section('content')
    <h4 class="py-3 mb-4">
        {{ $category->name }} → Sub Categories
    </h4>

    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary mb-3">
        ← Back to Categories
    </a>

    {{-- Category Info --}}
    <div class="card mb-4">
        <div class="card-body d-flex align-items-center">
            <img src="{{ asset('storage/' . $category->category_image) }}"
                style="width: 80px; height: 80px; border-radius: 10px; object-fit: cover;" class="me-3">

            <div>
                <h5 class="mb-1">{{ $category->name }}</h5>
                <p class="text-muted mb-0">{{ $category->description }}</p>
            </div>
        </div>
    </div>

    {{-- Show subcategories by type --}}
    @forelse ($subItems as $type => $items)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">{{ $type }}</h5>
            </div>

            <div class="card-body">

                {{-- Category Type Image --}}
                @if ($items->first()->category_type_image)
                    <img src="{{ asset('storage/' . $items->first()->category_type_image) }}"
                        style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;" class="mb-3">
                @endif

                <h6>Items:</h6>

                <div class="row">
                    @foreach ($items as $item)
                        <div class="col-md-3 mb-3">
                            <div class="card">
                                <div class="p-2 text-center">
                                    @if ($item->item_image)
                                        <img src="{{ asset('storage/' . $item->item_image) }}"
                                            style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                                    @else
                                        <span class="text-muted">No Item Image</span>
                                    @endif
                                </div>
                                <div class="card-body p-2 text-center">
                                    <strong>{{ $item->item }}</strong>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    @empty
        <div class="alert alert-warning">
            No sub category types or items found for this category.
        </div>
    @endforelse
@endsection
