<?php
final class BookingService
{
    private array $observers = [];

    public function addObserver(BookingConfirmedObserverInterface $observer): void
    {
        $this->observers[] = $observer;
    }

    public function confirm(Booking $booking, string $paymentMethod = 'stripe'): float
    {
        if (count($booking->items) === 0) {
            throw new RuntimeException('Empty booking');
        }
        
        if (!filter_var($booking->customer->email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('Invalid email');
        }
                 
        foreach ($booking->items as $item) {
            if ($item->quantity <= 0) {
                throw new RuntimeException('Invalid quantity');
            }
        }

        $calculator = new PositivePrice(
            new ThreeDaysDiscount(
                new VipDiscount(
                    new BasePriceCalculator()
                )
            )
        );
        $total = $calculator->calculate($booking);

        if ($paymentMethod === 'stripe') {
            $stripe = new StripeClient();
            $transactionId = $stripe->charge($total);
            echo "PAYMENT {$transactionId}" . PHP_EOL;
        } elseif ($paymentMethod === 'payfast') {
            throw new RuntimeException('PayFast not implemented');
        } else {
            throw new RuntimeException('Unknown payment method');
        }

        $booking->status = 'confirmed';
        echo "SQL INSERT booking={$booking->id} total={$total} status={$booking->status}" . PHP_EOL;


        foreach ($this->observers as $observer) {
            $observer->onBookingConfirmed($booking, $total);
        }

        return $total;
    }
}