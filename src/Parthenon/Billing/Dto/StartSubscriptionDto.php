<?php

namespace Parthenon\Billing\Dto;

use Symfony\Component\Serializer\Annotation\SerializedName;

class StartSubscriptionDto
{
    #[SerializedName('plan_name')]
    private string $planName;

    #[SerializedName('seat_numbers')]
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