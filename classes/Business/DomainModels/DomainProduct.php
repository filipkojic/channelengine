<?php

namespace ChannelEngine\PrestaShop\Classes\Business\DomainModels;

/**
 * Domain model representing a product in the system.
 * This model is used to encapsulate product data and provide
 * a structured way of managing product-related operations.
 */
class DomainProduct
{
    /**
     * @var int The product's unique identifier.
     */
    protected $id;

    /**
     * @var string The name of the product.
     */
    protected $name;

    /**
     * @var string A brief description of the product.
     */
    protected $description;

    /**
     * @var float The price of the product.
     */
    protected $price;

    /**
     * Constructor for the DomainProduct class.
     *
     * @param int $id The product's unique identifier.
     * @param string $name The name of the product.
     * @param string $description A brief description of the product.
     * @param float $price The price of the product.
     */
    public function __construct(int $id, string $name, string $description, float $price)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
    }

    /**
     * Converts the product object to an associative array.
     *
     * This method is typically used when preparing product data
     * for API requests or other processes that require array structures.
     *
     * @return array The product data in an associative array format.
     */
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
