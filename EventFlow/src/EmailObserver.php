<?php

class EmailObserver implements BookingConfirmedObserverInterface {
    public function __construct(private EmailService $emailService) {

    }

    public function onBookingConfirmed(Booking $booking, float $totalPaid): void {
        $this->emailService->sendConfirmation($booking->customer->email, $booking->id);
    }
}