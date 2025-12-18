<?php
namespace Cmrweb\StripeBundle\Model;

class Address
{
    private string $city;
    private string $country;
    private string $line1;
    private string $postalCode;
    private string $state;

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;
        return $this;
    }

    public function getLine1(): string
    {
        return $this->line1;
    }

    public function setLine1(string $line1): self
    {
        $this->line1 = $line1;
        return $this;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;
        return $this;
    }
    
    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;
        return $this;
    }
        
    public function toArray(): array
    {
        return [
            'city'       => $this->city,
            'country'    => $this->country,
            'line1'      => $this->line1,
            'postal_code' => $this->postalCode,
            'state'      => $this->state,
        ];
    }
}