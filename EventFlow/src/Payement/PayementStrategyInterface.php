<?php

interface PayementStrategyInterface
{
    public function paid(float $amount): string;
}