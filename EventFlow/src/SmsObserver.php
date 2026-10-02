<?php

final class SmsObserver implements BookingConfirmedObserverInterface
{
    public function __construct(private SmsClient $smsClient)
    {
    }

    public function onBookingConfirmed(Booking $booking, float $totalPaid): void
    {
        if ($booking->customer->phone !== null) {
            $this->smsClient->send($booking->customer->phone, 'Your booking has been confirmed.');
        }
    }
}