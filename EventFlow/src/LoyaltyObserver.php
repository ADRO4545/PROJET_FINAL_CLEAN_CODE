<?php

class LoyaltyObserver implements BookingConfirmedObserverInterface {
    private function __construct(private LoyaltyService $loyaltyService) {
    }

    public function onBookingConfirmed(Booking $booking, float $totalPaid): void {
        $points = (int) $totalPaid;
        $this->loyaltyService->addPoints($booking->customer->id, $points);
    }
}