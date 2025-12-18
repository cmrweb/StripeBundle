<?php
namespace Cmrweb\StripeBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Product
{
    private ?string $id = null;

    private ?string $name = null;

    private ?string $description = null; 
 
    private ?string $defaultPrice = null;
    /**
     * @var Collection<int, Price>
     */ 
    private Collection $prices;
 
    private ?bool $active = null;

    /**
     * @var Collection<int, Coupon>
     */ 
    private Collection $coupons;

    private ?array $images = null;

    private ?int $quantity = null;

    public function __construct()
    {
        $this->prices = new ArrayCollection();
        $this->coupons = new ArrayCollection();
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
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    } 

    /**
     * @return Collection<int, Price>
     */
    public function getPrices(): Collection
    {
        return $this->prices;
    }

    public function addPrice(Price $price): static
    {
        if (!$this->prices->contains($price)) {
            $this->prices->add($price);
            $price->setProduct($this);
        }

        return $this;
    }

    public function removePrice(Price $price): static
    {
        if ($this->prices->removeElement($price)) {
            // set the owning side to null (unless already changed)
            if ($price->getProduct() === $this) {
                $price->setProduct(null);
            }
        }

        return $this;
    }

    public function getDefaultPrice(): ?string
    {
        return $this->defaultPrice;
    }

    public function setDefaultPrice(?string $defaultPrice): static
    {
        $this->defaultPrice = $defaultPrice;
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

    /**
     * @return Collection<int, Coupon>
     */
    public function getCoupons(): Collection
    {
        return $this->coupons;
    }

    public function addCoupon(Coupon $coupon): static
    {
        if (!$this->coupons->contains($coupon)) {
            $this->coupons->add($coupon);
            $coupon->addAppliesTo($this);
        }

        return $this;
    }

    public function removeCoupon(Coupon $coupon): static
    {
        if ($this->coupons->removeElement($coupon)) {
            $coupon->removeAppliesTo($this);
        }

        return $this;
    }

    public function getImages(): ?array
    {
        return $this->images;
    }

    public function setImages(?array $images): static
    {
        $this->images = $images;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): static
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getLineItem(): array
    {
        return [
            'price' => $this->defaultPrice,
            'quantity' => $this->quantity
        ];
    }
}
