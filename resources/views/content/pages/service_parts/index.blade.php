@extends('layouts/contentNavbarLayout')

@section('title', 'Service Parts')

@section('content')

    <h4 class="py-3 mb-4">Service Parts</h4>

    <a href="{{ route('admin.serviceParts.create') }}" class="btn btn-primary mb-3">
        + Add Service Part
    </a>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Part Name</th>
                        <th>Base Cost</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($parts as $part)
                        <tr>
                            <td>{{ $part->category->name ?? 'N/A' }}</td>
                            <td>{{ $part->part_name }}</td>
                            <td>{{ $part->base_cost }}</td>

                            <td>
                                <a href="{{ route('admin.serviceParts.edit', $part->id) }}" class="btn btn-sm btn-secondary">
                                    Edit
                                </a>

                                <form action="{{ route('admin.serviceParts.destroy', $part->id) }}" method="POST"
                                    class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')

                                    <button type="button" class="btn btn-sm btn-danger delete-btn">
                                        Delete
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection


{{-- SWEETALERT SCRIPT --}}
@section('page-script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // All delete buttons
            const deleteButtons = document.querySelectorAll('.delete-btn');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {

                    let form = this.closest('form');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This service part will be permanently deleted.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Delete',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });

                });
            });

        });
    </script>
@endsection
