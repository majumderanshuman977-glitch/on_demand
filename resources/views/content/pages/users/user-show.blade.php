@extends('layouts/contentNavbarLayout')

@section('title', 'User Profile')

@section('content')

    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Users /</span> {{ $user->name }}
    </h4>

    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary mb-3">
        ← Back to Users
    </a>

    {{-- Profile Info --}}
    <div class="card mb-4">
        <div class="card-body d-flex align-items-start gap-4">
            <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('assets/img/avatars/1.png') }}"
                class="d-block w-px-120 h-px-120 rounded" />

            <div>
                <h4 class="mb-1">{{ $user->name }}</h4>
                <p class="mb-1">{{ $user->email }}</p>
                <p class="text-muted mb-1">{{ $user->phone ?? 'No Phone' }}</p>

                @if ($user->is_verified)
                    <span class="badge bg-label-success">Verified</span>
                @else
                    <span class="badge bg-label-secondary">Not Verified</span>
                @endif
            </div>
        </div>
    </div>

    {{-- User Information --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5>User Information</h5>
            <div class="row mt-3 gy-4">
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" disabled value="{{ $user->name }}">
                        <label>Name</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="email" disabled value="{{ $user->email }}">
                        <label>Email</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" disabled value="{{ $user->phone }}">
                        <label>Phone</label>
                    </div>
                </div>

                @if ($user->role === 'provider')
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" disabled value="{{ $user->categories->name ?? '' }}">
                        <label>Category</label>
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- KYC Document for Providers --}}
    @if ($user->role === 'provider' && $user->kyc_document)
        <div class="card mb-4">
            <div class="card-body">
                <h5>KYC Document</h5>
                <div class="mt-3">
                    <img id="kyc-img" src="{{ asset('storage/' . $user->kyc_document) }}" alt="KYC Document"
                        style="max-width: 300px; cursor: pointer; border: 1px solid #ccc; border-radius: 5px;">
                </div>
            </div>
        </div>

        {{-- Modal for zoom --}}
        <div id="kycModal"
            style="display:none; position:fixed; z-index:1050; padding-top:100px; left:0; top:0; width:100%; height:100%; overflow:auto; background-color:rgba(0,0,0,0.8);">
            <span id="closeModal"
                style="position:absolute; top:20px; right:35px; color:#fff; font-size:40px; font-weight:bold; cursor:pointer;">&times;</span>
            <img id="modal-img" style="margin:auto; display:block; max-width:90%; max-height:80%;">
        </div>
    @endif

    {{-- User Addresses --}}
    <div class="card">
        <div class="card-body">


            @if ($user->role === 'user')

                <h5>Addresses</h5>

                @if ($user->UserAddress->count() > 0)
                    @foreach ($user->UserAddress as $address)
                        <div class="border p-3 mb-3 rounded">
                            <p><strong>Apartment:</strong> {{ $address->apartment_number ?? '-' }}</p>
                            <p><strong>Street:</strong> {{ $address->street_address ?? '-' }}</p>
                            <p><strong>City:</strong> {{ $address->city ?? '-' }}</p>
                            <p><strong>State:</strong> {{ $address->state ?? '-' }}</p>
                            <p><strong>Pin Code:</strong> {{ $address->pin_code ?? '-' }}</p>
                            <p><strong>Country:</strong> {{ $address->country ?? '-' }}</p>
                            <p><strong>Contact:</strong> {{ $address->contact_phone_number ?? '-' }}</p>

                            <p>
                                <strong>Status:</strong>
                                @if ($address->is_active)
                                    <span class="badge bg-label-success">Active</span>
                                @else
                                    <span class="badge bg-label-secondary">Inactive</span>
                                @endif
                            </p>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No addresses added yet.</p>
                @endif
            @else
                <h5>Provider Location </h5>
                <div class="form-floating form-floating-outline">
                    <input class="form-control" type="text" disabled value="{{ $user->address }}">

                </div>
            @endif

        </div>
    </div>


    {{-- User / Provider Bookings --}}
    @if ($user->role === 'users')
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="mb-3">Bookings</h5>

                @if ($user->userBookings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Provider</th>
                                    <th>Status</th>
                                    <th>Scheduled Date</th>
                                    <th>Scheduled Time</th>
                                    <th>Amount</th>
                                    <th>Created</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    $count = 1;
                                @endphp
                                @foreach ($user->userBookings as $booking)
                                    <tr>
                                        <td>#{{ $count++ }}</td>

                                        {{-- Provider Name --}}
                                        <td>
                                            {{ $booking->provider ? $booking->provider->name : 'N/A' }}
                                        </td>

                                        <td>
                                            @if ($booking->status === 'accepted')
                                                <span class="badge bg-label-success">Accepted</span>
                                            @elseif($booking->status === 'pending')
                                                <span class="badge bg-label-warning">Pending</span>
                                            @elseif($booking->status === 'rejected')
                                                <span class="badge bg-label-danger">Rejected</span>
                                            @else
                                                <span
                                                    class="badge bg-label-secondary">{{ ucfirst($booking->status) }}</span>
                                            @endif
                                        </td>

                                        <td>{{ \Carbon\Carbon::parse($booking->scheduled_date)->format('d M Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($booking->scheduled_time)->format('h:i A') }}</td>

                                        <td><strong>Rs. {{ number_format($booking->total_amount, 2) }}</strong></td>

                                        <td>{{ $booking->created_at->format('d M Y h:i A') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No bookings recorded for this user.</p>
                @endif
            </div>
        </div>
    @else
    @endif


    {{-- Provider Bookings --}}
    @if ($user->role === 'provider')
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="mb-3">Received Bookings</h5>

                @if ($user->providerBookings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Amount</th>
                                    <th>Created</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($user->providerBookings as $booking)
                                    <tr>
                                        <td>#{{ $booking->id }}</td>

                                        {{-- Customer Name --}}
                                        <td>{{ $booking->user->name ?? 'N/A' }}</td>

                                        <td>
                                            @if ($booking->status === 'accepted')
                                                <span class="badge bg-label-success">Accepted</span>
                                            @elseif($booking->status === 'pending')
                                                <span class="badge bg-label-warning">Pending</span>
                                            @elseif($booking->status === 'rejected')
                                                <span class="badge bg-label-danger">Rejected</span>
                                            @else
                                                <span
                                                    class="badge bg-label-secondary">{{ ucfirst($booking->status) }}</span>
                                            @endif
                                        </td>

                                        <td>{{ \Carbon\Carbon::parse($booking->scheduled_date)->format('d M Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($booking->scheduled_time)->format('h:i A') }}</td>

                                        <td><strong>${{ number_format($booking->total_amount, 2) }}</strong></td>

                                        <td>{{ $booking->created_at->format('d M Y h:i A') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No bookings assigned to this provider.</p>
                @endif
            </div>
        </div>
    @endif







    @if ($user->role === 'provider' && $user->kyc_document)
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const modal = document.getElementById("kycModal");
                const img = document.getElementById("kyc-img");
                const modalImg = document.getElementById("modal-img");
                const closeModal = document.getElementById("closeModal");

                img.onclick = function() {
                    modal.style.display = "block";
                    modalImg.src = this.src;
                }

                closeModal.onclick = function() {
                    modal.style.display = "none";
                }

                window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = "none";
                    }
                }
            });
        </script>
    @endif

@endsection
