<?php
declare(strict_types=1);

final class LoggedPayementStrategy implements PayementStrategyInterface
{
    public function __construct(
        private PayementStrategyInterface $strategy
    ) {}

    public function paid(float $amount): string
    {
        echo "LOG [Supervision]: Début du paiement - Montant demandé : " . number_format($amount, 2, '.', '') . " EUR" . PHP_EOL;
        $startTime = microtime(true);

        try {
            $transactionId = $this->strategy->paid($amount);
            $durationMs = round((microtime(true) - $startTime) * 1000, 2);
            echo "LOG [Supervision]: Succès du paiement [Transaction ID: {$transactionId}] - Durée : {$durationMs}ms" . PHP_EOL;
            return $transactionId;
            
        } catch (Throwable $e) {
            $durationMs = round((microtime(true) - $startTime) * 1000, 2);
            echo "LOG [Supervision]: Échec du paiement - Durée : {$durationMs}ms - Erreur : " . $e->getMessage() . PHP_EOL;
            throw $e;
        }
    }
}