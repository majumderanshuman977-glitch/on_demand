@extends('layouts/contentNavbarLayout')

@section('title', 'Services')

@section('content')

    <h4 class="py-3 mb-4">Services</h4>

    <a href="{{ route('admin.services.create') }}" class="btn btn-primary mb-3">+ Add Service</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Service Image</th>
                        <th>Sub Category Item</th>
                        <th>Price</th>
                        <th>Offer Price</th>
                        <th>Duration</th>
                        <th>Includes</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($services as $srv)
                        <tr>
                            <td>{{ $srv->title }}</td>
                            <td>
                                @if ($srv->services_image)
                                    <img src="{{ asset($srv->services_image) }}" alt="{{ $srv->title }}" width="80"
                                        height="80" style="object-fit:cover;">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>{{ $srv->subCategoryItem->type }}</td>
                            <td>{{ $srv->price }}</td>
                            <td>{{ $srv->offer_price }}</td>
                            <td>{{ $srv->duration }} hrs</td>
                            <td>
                                @foreach ($srv->includes ?? [] as $inc)
                                    <span class="badge bg-label-secondary">{{ $inc }}</span>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('admin.services.edit', $srv->id) }}"
                                    class="btn btn-sm btn-secondary">Edit</a>

                                <form action="{{ route('admin.services.destroy', $srv->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Delete service?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

@endsection
