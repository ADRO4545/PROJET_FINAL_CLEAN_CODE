<?php

class PositivePrice implements PriceCalculatorInterface {
    public function __construct(private PriceCalculatorInterface $price_calculator) 
    {}

    public function calculate(Booking $booking): float {
        $total = $this->price_calculator->calculate($booking);

        if ($total < 0) {
            return 0;
        }

        return $total;
    }
}