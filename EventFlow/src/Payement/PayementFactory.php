<?php
declare(strict_types=1);

final class PaymentFactory
{
    public static function create(string $type): PayementStrategy
    {
        switch ($type) {
            case 'stripe':
                return new StripeStrategy(new StripeClient());
            case 'payfast':
                return new PayFastAdapter(new PayFastSdk());
            default:
                throw new InvalidArgumentException(
                    sprintf('Le moyen de paiement "%s" n\'est pas supporté.', $type)
                );
        }
    }
}