<?php
final class LoyaltyObserver implements BookingConfirmedObserverInterface
{
    public function __construct(private LoyaltyService $loyaltyService)
    {
    }

    public function onBookingConfirmed(Booking $booking, float $totalPaid): void
    {
        $points = (int) $totalPaid;
        $this->loyaltyService->addPoints($booking->customer->id, $points);
    }
}