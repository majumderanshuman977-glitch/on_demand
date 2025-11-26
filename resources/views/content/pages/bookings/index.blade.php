@extends('layouts/contentNavbarLayout')

@section('title', 'Bookings')

@section('content')

    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Bookings /</span> All Bookings
    </h4>

    <a href="{{ route('dashboard-analytics') }}" class="btn btn-secondary mb-3">
        ← Back to Dashboard
    </a>

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>User</th>
                            <th>Provider</th>
                            <th>Job Title</th>
                            <th>Status</th>
                            <th>Total Amount</th>
                            <th>Created At</th>
                            <th>Conversation</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td>#{{ $booking->id }}</td>
                                <td>{{ $booking->user->name ?? 'N/A' }}</td>
                                <td>{{ $booking->provider->name ?? 'N/A' }}</td>
                                <td>{{ $booking->job_title ?? 'N/A' }}</td>

                                <td>
                                    <span
                                        class="badge
                                        @if ($booking->status == 'pending') bg-warning
                                        @elseif($booking->status == 'accepted') bg-success
                                        @elseif($booking->status == 'cancelled') bg-danger
                                        @else bg-secondary @endif">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>

                                <td>${{ number_format($booking->total_amount, 2) }}</td>

                                <td>{{ $booking->created_at->format('d M Y') }}</td>

                                <td>
                                    <a href="{{ route('conversation.show', $booking->id) }}" class="btn btn-primary btn-sm">
                                        View Chat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No bookings available</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

@endsection
