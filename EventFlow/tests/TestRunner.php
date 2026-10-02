<?php
declare(strict_types=1);

final class TestRunner
{
    private int $passed = 0;
    private int $failed = 0;

    public function same(mixed $expected, mixed $actual, string $label): void
    {
        $isSuccess = ($expected === $actual);
        
        $this->recordResult(
            $isSuccess,
            $label,
            var_export($expected, true),
            var_export($actual, true)
        );
    }

    public function near(float $expected, float $actual, string $label, float $delta = 0.001): void
    {
        $isSuccess = (abs($expected - $actual) <= $delta);
        
        $this->recordResult(
            $isSuccess,
            $label,
            (string) $expected,
            (string) $actual
        );
    }

    private function recordResult(bool $isSuccess, string $label, string $expectedMsg, string $actualMsg): void
    {
        if ($isSuccess) {
            $this->passed++;
            echo "OK   {$label}" . PHP_EOL;
            return;
        }

        $this->failed++;
        echo "FAIL {$label}" . PHP_EOL;
        echo "     expected: {$expectedMsg}" . PHP_EOL;
        echo "     actual:   {$actualMsg}" . PHP_EOL;
    }

    public function summary(): void
    {
        echo PHP_EOL . "Passed: {$this->passed}, Failed: {$this->failed}" . PHP_EOL;
        if ($this->failed > 0) {
            exit(1);
        }
    }
}