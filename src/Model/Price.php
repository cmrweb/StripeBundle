<?php
namespace Cmrweb\StripeBundle\Model;

use Cmrweb\StripeBundle\Enum\ReccuringPriceEnum;
use Symfony\Component\Serializer\Attribute\Ignore;

class Price
{
    private ?string $id = null;
    private string $currency = 'eur';
    #[Ignore]
    private ?Product $product = null;
    private ?bool $active = null;
    private ?int $unitAmount = null;
    private ?ReccuringPriceEnum $reccuring = null;
    private ?int $intervalCount = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $isActive): static
    {
        $this->active = $isActive;

        return $this;
    }

    public function getUnitAmount(): ?int
    {
        return $this->unitAmount;
    }

    public function setUnitAmount(int $unitAmount): static
    {
        $this->unitAmount = $unitAmount;

        return $this;
    }

    public function getReccuring(): ?ReccuringPriceEnum
    {
        return $this->reccuring;
    }

    public function setReccuring(?ReccuringPriceEnum $reccuring): static
    {
        $this->reccuring = $reccuring;

        return $this;
    }

    public function getIntervalCount(): ?int
    {
        return $this->intervalCount;
    }

    public function setIntervalCount(?int $intervalCount): static
    {
        $this->intervalCount = $intervalCount;

        return $this;
    }
}
