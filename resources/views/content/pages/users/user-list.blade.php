@extends('layouts/contentNavbarLayout')

@section('title', 'Users Management')

@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Users /</span> All Users
    </h4>

    <div class="row">
        <div class="col-md-12">

            <ul class="nav nav-pills flex-column flex-md-row mb-4 gap-2 gap-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="javascript:void(0);">
                        <i class="mdi mdi-account-multiple-outline mdi-20px me-1"></i>Users
                    </a>
                </li>
            </ul>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Customers</h5>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Profile</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Verified?</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($customers as $user)
                                <tr>
                                    <td>
                                        <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('assets/img/avatars/1.png') }}"
                                            class="rounded" width="40" height="40">
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone ?? '-' }}</td>
                                    <td>
                                        @if ($user->is_verified)
                                            <span class="badge bg-label-success">Verified</span>
                                        @else
                                            <span class="badge bg-label-secondary">Not Verified</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                            class="btn btn-sm btn-secondary">Edit</a>
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-primary">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $customers->links() }}
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title">Providers</h5>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Profile</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Verified?</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($providers as $user)
                                <tr>
                                    <td>
                                        <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('assets/img/avatars/1.png') }}"
                                            class="rounded" width="40" height="40">
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone ?? '-' }}</td>
                                    <td>
                                        @if ($user->is_verified)
                                            <span class="badge bg-label-success">Verified</span>
                                        @else
                                            <span class="badge bg-label-secondary">Not Verified</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                            class="btn btn-sm btn-secondary">Edit</a>

                                        <a href="{{ route('admin.users.show', $user->id) }}"
                                            class="btn btn-sm btn-primary">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $providers->links() }}
                </div>
            </div>

        </div>
    </div>
@endsection
