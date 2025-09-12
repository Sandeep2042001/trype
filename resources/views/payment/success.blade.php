@extends('layouts.app')

@section('title', 'Payment Successful')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                    </div>
                    <h2 class="card-title mb-4">Payment Successful!</h2>
                    
                    @if(session('hotel_booking'))
                        @php $booking = session('hotel_booking'); @endphp
                        <div class="alert alert-success mb-4">
                            <h5 class="alert-heading">Hotel Booking Confirmed!</h5>
                            <p class="mb-2"><strong>Hotel:</strong> {{ $booking['hotel_name'] }}</p>
                            <p class="mb-2"><strong>Location:</strong> {{ $booking['destination_name'] }}</p>
                            <p class="mb-2"><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($booking['check_in'])->format('M d, Y') }}</p>
                            <p class="mb-2"><strong>Check-out:</strong> {{ \Carbon\Carbon::parse($booking['check_out'])->format('M d, Y') }}</p>
                            <p class="mb-2"><strong>Guests:</strong> {{ $booking['guests'] }}</p>
                            <p class="mb-0"><strong>Total Amount:</strong> ${{ number_format($booking['total_amount'], 2) }}</p>
                        </div>
                        <p class="card-text mb-4">Your hotel booking has been successfully confirmed! You will receive a confirmation email shortly.</p>
                    @else
                        <p class="card-text mb-4">Your payment has been successfully processed. Thank you for your purchase!</p>
                    @endif
                    
                    @if(isset($transactionId))
                        <div class="alert alert-info mb-4">
                            <strong>Transaction ID:</strong> {{ $transactionId }}
                        </div>
                        <p class="small text-muted">Please save this transaction ID for your records.</p>
                    @endif
                    
                    <div class="mt-5">
                        <a href="{{ route('home') }}" class="btn btn-primary">Return to Homepage</a>
                        @if(session('hotel_booking'))
                            <a href="{{ route('hotels.show', ['destination' => session('hotel_booking.destination_id'), 'hotel' => session('hotel_booking.hotel_id')]) }}" class="btn btn-outline-primary ms-2">View Hotel</a>
                        @else
                            <a href="{{ route('user.bookings.index') }}" class="btn btn-outline-primary ms-2">View My Bookings</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
