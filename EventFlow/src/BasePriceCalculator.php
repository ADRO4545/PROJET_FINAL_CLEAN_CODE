<?php

final class BasePriceCalculator implements PriceCalculatorInterface {
    public function calculate(Booking $booking): float {
        $total = 0.0;
        foreach ($booking->items as $item) {
            $total += $item->ticket->price * $item->quantity;
        }
        return $total;
    }
}