<?php

final class PaymentFactory
{
    public static function create(string $type): PayementStrategyInterface
    {
        $strategy = match ($type) {
            'stripe' => new StripeStrategy(new StripeClient()),
            'payfast' => new PayFastAdapter(new PayFastSdk()),
            default => throw new InvalidArgumentException(
                sprintf('Le moyen de paiement "%s" n\'est pas supporté.', $type)
            ),
        };

        return new LoggedPayementStrategy($strategy);
    }
}