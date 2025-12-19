<?php

namespace Cmrweb\StripeBundle\Service;
 
use Cmrweb\StripeBundle\Model\Customer;
use Cmrweb\StripeBundle\Model\Price;
use Cmrweb\StripeBundle\Model\Product; 
use Stripe\StripeClient;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\NameConverter\SnakeCaseToCamelCaseNameConverter;
use Symfony\Component\Serializer\Serializer;

class StripeService
{
    private StripeClient $stripeClient;
    protected Serializer $serializer;

    public function __construct(
        protected string $returnUrl,
        protected readonly ParameterBagInterface $param
    ) {
        $propertyInfo = new PropertyInfoExtractor([], [new PhpDocExtractor(), new ReflectionExtractor()]);
        $normalizers = [new ObjectNormalizer(new ClassMetadataFactory(new AttributeLoader()), new SnakeCaseToCamelCaseNameConverter(), null, $propertyInfo), new ArrayDenormalizer(new ClassMetadataFactory(new AttributeLoader()), new CamelCaseToSnakeCaseNameConverter(), null, $propertyInfo)];
        $this->serializer = new Serializer($normalizers, [new JsonEncoder()]);

        $this->stripeClient = new StripeClient($param->get('cmrweb.stripe.key.private'));
    }

    public function createCustomer(Customer $customer): Customer
    {
        if (null === $customer->getId()) { 
            $stripeCustomer = $this->stripeClient->customers->create($customer->toArray());
            $customer->setId($stripeCustomer->id);
            return $customer;
        }
        return $this->getCustomer($customer);
    }

    public function getCustomer(Customer $customer): Customer
    {
        return $this->serializer->denormalize($this->stripeClient->customers->retrieve($customer->getId())->toArray(), Customer::class);
    }

    public function getPrice(Price $price): Price
    {
        return $this->serializer->denormalize($this->stripeClient->prices->retrieve($price->getId())->toArray(), Price::class);
    }

    public function getProduct(Product $product): Product
    {
        return $this->serializer->denormalize($this->stripeClient->products->retrieve($product->getId())->toArray(), Product::class);
    }

    /**
     * @param Customer $customer
     * @param Product[] $cart
     */
    public function createCheckoutSession(Customer $customer, array $cart)
    {   
        $cart = [
            'customer' => $customer->getId(),
            'line_items' => [...$cart],
            'mode' => 'payment',
            "payment_method_types" => [
                "card"
            ],
            'ui_mode' => 'embedded',
            'return_url' => $this->returnUrl.'/{CHECKOUT_SESSION_ID}'
        ];

        return $this->stripeClient->checkout->sessions->create($cart);
    }

    public function createPaymentLink(string $label, int $amount, int $quantity = 1)
    {
        return $this->stripeClient->paymentLinks->create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'unit_amount' => $amount,
                        'product_data' => [
                            'name' => $label
                        ]
                    ],
                    'quantity' => $quantity
                ]
            ],
        ]);
    }


    public function createProduct(Product $product): Product
    {
        $stripeProduct = $this->stripeClient->products->create([
            "name" => $product->getName(),
            "active" => $product->isActive(),
            "description" => $product->getDescription()
        ]);
        $product->setId($stripeProduct->id);
        $price = $this->createPrice($product);
        $product = $this->updateProduct($product->getId(), [
            'default_price' => $price->getId()
        ]);
        return $product->setDefaultPrice($price->getId())->addPrice($price);
    }

    public function createPrice(Product $product): Price
    {
        $price = $product->getPrices()[0];
        $data = [
            'currency' => 'eur',
            'unit_amount' => $price->getUnitAmount(),
            'product' => $price->getProduct()->getId(),
        ];
        if ($price->getReccuring()) {
            $data['recurring'] = ['interval' => $price->getReccuring()->value, 'interval_count' => $price->getIntervalCount() ?? 1];
        }
        $stripePrice = $this->stripeClient->prices->create($data);
        $price->setId($stripePrice->id);
        return $price;
    }

    private function updateProduct(string $productId, array $param): Product
    {
        return $this->serializer->denormalize($this->stripeClient->products->update($productId, $param)->toArray(), Product::class);
    }
}
