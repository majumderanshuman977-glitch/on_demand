@extends('layouts/contentNavbarLayout')

@section('title', 'Service Parts Categories')

@section('content')
    <h4 class="py-3 mb-4">Service Parts Categories</h4>

    <a href="{{ route('admin.servicePartsCategories.create') }}" class="btn btn-primary mb-3">
        + Add Category
    </a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card p-3">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width="60">#</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th width="150">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->description }}</td>
                        <td>

                            <a href="{{ route('admin.servicePartsCategories.edit', $category->id) }}"
                                class="btn btn-sm btn-info">Edit</a>

                            <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $category->id }})">
                                Delete
                            </button>

                            <form id="delete-form-{{ $category->id }}"
                                action="{{ route('admin.servicePartsCategories.destroy', $category->id) }}" method="POST"
                                style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No categories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection


@section('scripts')
    {{-- SweetAlert CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "This category will be permanently deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endsection
