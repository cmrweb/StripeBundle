<?php

namespace Cmrweb\StripeBundle\Tests\Service;

use Cmrweb\StripeBundle\Model\Address;
use Cmrweb\StripeBundle\Model\Cart;
use Cmrweb\StripeBundle\Model\Customer;
use Cmrweb\StripeBundle\Model\Price;
use Cmrweb\StripeBundle\Model\Product;
use Cmrweb\StripeBundle\Service\StripeService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Depends;
use Stripe\Customer as StripeCustomer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class StripeServiceTest extends KernelTestCase
{
    private StripeService $stripeService;
    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->stripeService = $container->get(StripeService::class);
    }

    #[DataProvider('customerProvider')]
    public function testCreateCustomer(Customer $customer): void
    {
        $this->assertInstanceOf(StripeService::class, $this->stripeService);

        $stripeCustomer = $this->stripeService->createCustomer($customer);
        $this->assertInstanceOf(Customer::class, $stripeCustomer);
        $this->assertNotNull($customer->getId()); 

        $stripeCustomer = $this->stripeService->getCustomer($customer);
        $this->assertInstanceOf(Customer::class, $stripeCustomer);
        restore_exception_handler();
    }

    public function testCreateProduct(): void
    { 
        $product = $this->stripeService->createProduct((new Product)->setName('produit 1')
        ->setDescription('mon produit 1')
        ->setActive(true)
        ->addPrice((new Price)->setUnitAmount(2000)));
 
        $this->assertNotNull($product->getId()); 
        $this->assertNotNull($product->getPrices()->first()->getUnitAmount()); 

        $product = $this->stripeService->getProduct($product);
        $price =  $this->stripeService->getPrice((new Price)->setId($product->getDefaultPrice()));
        
        $this->assertNotNull($product->getDefaultPrice());
        $this->assertSame($price->getId(), $product->getDefaultPrice());
        
        restore_exception_handler();
    }

    public function testPaymentLink(): void
    {
        $payementLink = $this->stripeService->createPaymentLink('Mon payement test de 20€', 2000);
        
        $this->assertNotNull($payementLink?->url);
        dump($payementLink?->url);
        restore_exception_handler();
    }

    #[DataProvider('customerProvider')]
    public function testCheckoutSession(Customer $customer): void
    {        
        $stripeCustomer = $this->stripeService->createCustomer($customer);
        $product = $this->stripeService->getProduct((new Product)->setId('prod_TYrGf8leiagyTz'))->setDefaultPrice('price_1SbipmFzRRUZwQJRHJ2OAtNV');
        
        $cart = [$product->setQuantity(3)->getLineItem()];
        $checkout = $this->stripeService->createCheckoutSession($stripeCustomer, $cart);
 
        $this->assertIsString($checkout->client_secret);
        dump($checkout->client_secret);
        restore_exception_handler();
    }

    public static function customerProvider(): array
    {
        return [
            "cmrweb" => [
                (new Customer()) 
                ->setEmail("cmrweb@example.com")
                ->setName("cmrweb stripe")
                ->setPhone("0600000000")
                ->setAddress(
                    (new Address())
                        ->setCity("Paris")
                        ->setCountry("France")
                        ->setLine1("10 Rue de la Paix")
                        ->setPostalCode("75002")
                        ->setState("Île-de-France")
                )
            ]
        ];
    }
}
