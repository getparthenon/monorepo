<?php

declare(strict_types=1);

/*
 * Copyright Humbly Arrogant Software Limited 2020-2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: 26.06.2026 ( 3 years after 2.2.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace Obol\Model;

class Customer
{
    protected ?string $id = null;

    protected ?string $name = '';

    protected ?string $description = '';

    protected ?string $url = null;

    protected ?string $email = '';

    protected ?string $defaultSource = '';

    protected Address $address;

    protected ?\DateTime $createdAt = null;

    protected bool $taxExempt = false;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function isTaxExempt(): bool
    {
        return $this->taxExempt;
    }

    public function setTaxExempt(bool $taxExempt): void
    {
        $this->taxExempt = $taxExempt;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    public function getDefaultSource(): ?string
    {
        return $this->defaultSource;
    }

    public function setDefaultSource(?string $defaultSource): void
    {
        $this->defaultSource = $defaultSource;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
