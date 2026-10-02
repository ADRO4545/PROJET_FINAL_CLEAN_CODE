<?php

interface PayementStrategy
{
    public function paid(float $amount): string;
}