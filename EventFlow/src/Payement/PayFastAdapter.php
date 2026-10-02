<?php
declare(strict_types=1);

final class PayFastAdapter implements PayementStrategy
{
    private PayFastSdk $payFastSdk;

    public function __construct(PayFastSdk $payFastSdk)
    {
        $this->payFastSdk = $payFastSdk;
    }

    public function paid(float $amount): string
    {
        $payload = [
            'reference' => uniqid('order_'),
            'amount_cents' => (int) ($amount * 100),
            'currency' => 'EUR'
        ];

        $result = $this->payFastSdk->executePayment($payload);

        if (!$result['success']) {
            throw new RuntimeException('Le paiement via PayFast a échoué.');
        }

        return $result['transaction_id'];
    }
}