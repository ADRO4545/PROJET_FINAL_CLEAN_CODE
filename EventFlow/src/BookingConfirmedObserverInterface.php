<?php

interface BookingConfirmedObserverInterface {
    public function onBookingConfirmed(Booking $booking, float $totalPaid): void;

}