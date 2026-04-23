<?php

namespace Cmrweb\StripeBundle\Contract;

use Cmrweb\StripeBundle\Model\Customer;
use Cmrweb\StripeBundle\Model\Price;
use Cmrweb\StripeBundle\Model\Product;

interface StripeServiceInterface
{
    public function createCustomer(Customer $customer): Customer;

    public function getCustomer(Customer $customer): Customer;

    public function createProduct(Product $product): Product;

    public function getProduct(Product $product): Product;

    public function createPrice(Product $product): Price;

    public function getPrice(Price $price): Price;

    /**
     * @param Product[] $cart  array of line items [['price' => 'price_xxx', 'quantity' => n]]
     */
    public function createCheckoutSession(Customer $customer, array $cart): mixed;

    public function createPaymentLink(string $label, int $amount, int $quantity = 1): mixed;
}
