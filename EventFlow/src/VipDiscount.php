<?php

class VipDiscount implements PriceCalculatorInterface {
    public function __construct(private PriceCalculatorInterface $price_calculator) 
    {}

    public function calculate(Booking $booking): float {
        $total = $this->price_calculator->calculate($booking);

        if ($booking->customer->type !== 'vip') {
            return $total;
        }

        if ($total < 100) {
            return $total * 0.95;
        }
        if ($total < 300 && $total >= 100) {
            return $total * 0.90;
        }
        if ($total >= 300) {
            return $total * 0.85;
        }

        return $total;
    }
}