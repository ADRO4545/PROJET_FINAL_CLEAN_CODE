<?php
class ThreeDaysDiscount implements PriceCalculatorInterface {
    public function __construct(
        private PriceCalculatorInterface $price_calculator
    ) {
    }

    public function calculate(Booking $booking): float {
        $total = $this->price_calculator->calculate($booking);

        if ($booking->passType !== '3days') {
            return $total;
        }

        return $total - 20.0;
    }
}