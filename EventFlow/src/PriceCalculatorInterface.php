<?php

interface PriceCalculatorInterface{
    public function calculate(Booking $booking): float;
}