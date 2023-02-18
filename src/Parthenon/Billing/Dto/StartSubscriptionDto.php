<?php

namespace Parthenon\Billing\Dto;

class StartSubscriptionDto
{
    private string $planName;

    private int $seatNumbers = 1;

    public function getPlanName(): string
    {
        return $this->planName;
    }

    public function setPlanName(string $planName): void
    {
        $this->planName = $planName;
    }

    public function getSeatNumbers(): int
    {
        return $this->seatNumbers;
    }

    public function setSeatNumbers(int $seatNumbers): void
    {
        $this->seatNumbers = $seatNumbers;
    }
}