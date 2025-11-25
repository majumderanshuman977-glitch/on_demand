@extends('layouts/contentNavbarLayout')

@section('title', 'Sub Category Items')

@section('content')
    <div class="container">
        <h2 class="mb-4">All Sub Category Items</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif


        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th>Category Type Image</th>
                    <th>Item</th>
                    <th>Item Image</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->id }}</td>

                        {{-- Category Name --}}
                        <td>{{ $item->category->name ?? 'N/A' }}</td>

                        <td>{{ $item->type }}</td>

                        {{-- Category Type Image --}}
                        <td>
                            @if ($item->category_type_image)
                                <img src="{{ asset('storage/' . $item->category_type_image) }}" width="60" height="60"
                                    style="object-fit: cover;">
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>

                        {{-- Item --}}
                        <td>{{ $item->item ?? '—' }}</td>

                        {{-- Item Image --}}
                        <td>
                            @if ($item->item_image)
                                <img src="{{ asset('storage/' . $item->item_image) }}" width="60" height="60"
                                    style="object-fit: cover;">
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('admin.subcategory-items.edit', $item->id) }}"
                                class="btn btn-sm btn-warning">Edit</a>

                            <form action="{{ route('admin.subcategory-items.destroy', $item->id) }}" method="POST"
                                class="delete-form d-inline">
                                @csrf
                                @method('DELETE')

                                <button type="button" class="btn btn-sm btn-danger btn-delete">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
@endsection


@section('page-script')
    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function() {

                    let form = this.closest('.delete-form');

                    Swal.fire({
                        title: "Are you sure?",
                        text: "This item will be permanently deleted!",
                        icon: "warning",
                        showCancelButton: true,

                        customClass: {
                            confirmButton: "swal2-confirm btn btn-danger me-3",
                            cancelButton: "swal2-cancel btn btn-outline-secondary"
                        },

                        confirmButtonText: "Yes, delete it!",
                    }).then(result => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });

                });
            });

        });
    </script>
@endsection
