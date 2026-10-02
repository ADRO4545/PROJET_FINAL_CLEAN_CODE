<?php

final class AnalyticsObserver implements BookingConfirmedObserverInterface
{
    public function __construct(private AnalyticsClient $analyticsClient)
    {
    }

    public function onBookingConfirmed(Booking $booking, float $totalPaid): void
    {
        $this->analyticsClient->track('booking_confirmed', [
            'booking_id' => $booking->id,
            'total' => $totalPaid
        ]);
    }
}