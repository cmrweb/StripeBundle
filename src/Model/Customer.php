<?php

namespace Cmrweb\StripeBundle\Model;

class Customer
{
    private ?string $id = null;
    private string $email;
    private ?Address $address = null;
    private ?string $description = null;
    private ?array $metadata = null;
    private ?string $name = null;
    private ?string $phone = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    public function setMetadata(?array $metadata): self
    {
        $this->metadata = $metadata;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'email'       => $this->email,
            'address'     => $this->address?->toArray() ?? null,
            'description' => $this->description,
            'metadata'    => $this->metadata,
            'name'        => $this->name,
            'phone'       => $this->phone,
        ];
    }
}
