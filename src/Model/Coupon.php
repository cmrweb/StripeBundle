<?php
namespace Cmrweb\StripeBundle\Model;

use Cmrweb\StripeBundle\Enum\CouponDurationEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Coupon
{
    private ?string $id = null;

    private ?float $percentOff = null;

    private ?int $durationInMonth = null;

    private ?string $name = null;

    /**
     * @var Collection<int, Product>
     */ 
    private Collection $appliesTo;

    private ?CouponDurationEnum $duration = null;

    private ?string $stripeId = null;

    private ?bool $isActive = null;

    private ?\DateTime $beginAt = null;

    private ?\DateTime $endAt = null;

    private ?bool $hasPromotionCode = null;

    private ?string $promotionCode = null;

    public function __construct()
    {
        $this->appliesTo = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getPercentOff(): ?float
    {
        return $this->percentOff;
    }

    public function setPercentOff(float $percentOff): static
    {
        $this->percentOff = $percentOff;

        return $this;
    }

    public function getDurationInMonth(): ?int
    {
        return $this->durationInMonth;
    }

    public function setDurationInMonth(?int $durationInMonth): static
    {
        $this->durationInMonth = $durationInMonth;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getAppliesTo(): Collection
    {
        return $this->appliesTo;
    }

    public function addAppliesTo(Product $appliesTo): static
    {
        if (!$this->appliesTo->contains($appliesTo)) {
            $this->appliesTo->add($appliesTo);
        }

        return $this;
    }

    public function removeAppliesTo(Product $appliesTo): static
    {
        $this->appliesTo->removeElement($appliesTo);

        return $this;
    }
    
    public function getDuration(): ?CouponDurationEnum
    {
        return $this->duration;
    }

    public function setDuration(CouponDurationEnum $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getStripeId(): ?string
    {
        return $this->stripeId;
    }

    public function setStripeId(?string $stripeId): static
    {
        $this->stripeId = $stripeId;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getBeginAt(): ?\DateTime
    {
        return $this->beginAt;
    }

    public function setBeginAt(?\DateTime $beginAt): static
    {
        $this->beginAt = $beginAt;

        return $this;
    }

    public function getEndAt(): ?\DateTime
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTime $endAt): static
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function hasPromotionCode(): ?bool
    {
        return $this->hasPromotionCode;
    }

    public function setHasPromotionCode(bool $hasPromotionCode): static
    {
        $this->hasPromotionCode = $hasPromotionCode;

        return $this;
    }

    public function getPromotionCode(): ?string
    {
        return $this->promotionCode;
    }

    public function setPromotionCode(?string $promotionCode): static
    {
        $this->promotionCode = $promotionCode;

        return $this;
    }
}
