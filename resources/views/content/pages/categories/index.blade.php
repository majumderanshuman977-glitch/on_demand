@extends('layouts/contentNavbarLayout')

@section('title', 'Categories')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Categories</h4>

        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="ti ti-plus"></i> Add Category
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;">Image</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th style="width: 250px;">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($categories as $cat)
                        <tr>

                            {{-- Category Image --}}
                            <td>
                                @if ($cat->category_image)
                                    <img src="{{ asset('storage/' . $cat->category_image) }}" alt="Category Image"
                                        class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <span class="text-muted small">No Image</span>
                                @endif
                            </td>

                            {{-- Title --}}
                            <td class="fw-semibold">{{ $cat->name }}</td>

                            {{-- Description --}}
                            <td>{{ $cat->description }}</td>

                            {{-- Actions --}}
                            <td>
                                <div class="d-flex flex-wrap gap-2">

                                    <a href="{{ route('admin.categories.edit', $cat->id) }}"
                                        class="btn btn-sm btn-outline-secondary">
                                        <i class="ti ti-edit"></i> Edit
                                    </a>

                                    <a href="{{ route('admin.categories.types', $cat->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-plus"></i> Items
                                    </a>

                                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST"
                                        class="delete-form d-inline">
                                        @csrf
                                        @method('DELETE')

                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete">
                                            <i class="ti ti-trash"></i> Delete
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                No categories found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

@endsection


@section('page-script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            document.querySelectorAll('.btn-delete').forEach(button => {

                button.addEventListener('click', function() {
                    let form = this.closest('.delete-form');

                    Swal.fire({
                            title: "Delete Category?",
                            text: "This action cannot be undone!",
                            icon: "warning",
                            showCancelButton: true,

                            customClass: {
                                confirmButton: "swal2-confirm btn btn-danger me-3",
                                cancelButton: "swal2-cancel btn btn-outline-secondary"
                            },

                            confirmButtonText: "Yes, Delete",
                        })
                        .then(result => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                });
            });

        });
    </script>
@endsection
