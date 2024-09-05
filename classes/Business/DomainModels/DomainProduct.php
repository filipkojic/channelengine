<?php

namespace ChannelEngine\PrestaShop\Classes\Business\DomainModels;

class DomainProduct
{
    protected $id;
    protected $name;
    protected $description;
    protected $price;

    public function __construct(int $id, string $name, string $description, float $price)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
    }

    public function toArray(): array
    {
        return [
            'MerchantProductNo' => $this->id,
            'Name' => $this->name,
            'Description' => $this->description,
            'Price' => $this->price,
        ];
    }
}
