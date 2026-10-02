<?php
declare(strict_types=1);

final class StripeStrategy implements PayementStrategyInterface
{
    private StripeClient $stripeClient;

    public function __construct(StripeClient $stripeClient)
    {
        $this->stripeClient = $stripeClient;
    }

    public function paid(float $amount): string
    {
        return $this->stripeClient->charge($amount);
    }
}