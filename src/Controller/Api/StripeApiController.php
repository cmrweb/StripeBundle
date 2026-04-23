<?php

namespace Cmrweb\StripeBundle\Controller\Api;

use Cmrweb\StripeBundle\Contract\StripeServiceInterface;
use Cmrweb\StripeBundle\Model\Address;
use Cmrweb\StripeBundle\Model\Customer;
use Cmrweb\StripeBundle\Model\Price;
use Cmrweb\StripeBundle\Model\Product;
use Cmrweb\StripeBundle\Enum\ReccuringPriceEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/stripe', name: 'cmrweb_stripe_api_')]
class StripeApiController extends AbstractController
{
    public function __construct(
        private readonly StripeServiceInterface $stripeService,
    ) {}

    #[Route('/customers', name: 'customer_create', methods: ['POST'])]
    public function createCustomer(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['email'])) {
            return $this->json(['error' => 'Le champ email est obligatoire.'], Response::HTTP_BAD_REQUEST);
        }

        $customer = (new Customer())->setEmail($data['email']);

        if (!empty($data['name'])) {
            $customer->setName($data['name']);
        }
        if (!empty($data['phone'])) {
            $customer->setPhone($data['phone']);
        }
        if (!empty($data['description'])) {
            $customer->setDescription($data['description']);
        }
        if (!empty($data['metadata'])) {
            $customer->setMetadata($data['metadata']);
        }
        if (!empty($data['address'])) {
            $customer->setAddress($this->buildAddress($data['address']));
        }

        $customer = $this->stripeService->createCustomer($customer);

        return $this->json($this->serializeCustomer($customer), Response::HTTP_CREATED);
    }

    #[Route('/customers/{id}', name: 'customer_get', methods: ['GET'])]
    public function getCustomer(string $id): JsonResponse
    {
        $customer = $this->stripeService->getCustomer((new Customer())->setId($id));

        return $this->json($this->serializeCustomer($customer));
    }

    #[Route('/products', name: 'product_create', methods: ['POST'])]
    public function createProduct(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['name'])) {
            return $this->json(['error' => 'Le champ name est obligatoire.'], Response::HTTP_BAD_REQUEST);
        }
        if (empty($data['prices'])) {
            return $this->json(['error' => 'Au moins un prix est requis.'], Response::HTTP_BAD_REQUEST);
        }

        $product = (new Product())
            ->setName($data['name'])
            ->setActive($data['active'] ?? true);

        if (!empty($data['description'])) {
            $product->setDescription($data['description']);
        }

        foreach ($data['prices'] as $priceData) {
            $price = (new Price())->setUnitAmount((int) $priceData['unitAmount']);

            if (!empty($priceData['currency'])) {
                $price->setCurrency($priceData['currency']);
            }
            if (!empty($priceData['recurring'])) {
                $price->setReccuring(ReccuringPriceEnum::from($priceData['recurring']));
            }
            if (!empty($priceData['intervalCount'])) {
                $price->setIntervalCount((int) $priceData['intervalCount']);
            }

            $product->addPrice($price);
        }

        $product = $this->stripeService->createProduct($product);

        return $this->json($this->serializeProduct($product), Response::HTTP_CREATED);
    }

    #[Route('/products/{id}', name: 'product_get', methods: ['GET'])]
    public function getProduct(string $id): JsonResponse
    {
        $product = $this->stripeService->getProduct((new Product())->setId($id));

        return $this->json($this->serializeProduct($product));
    }

    #[Route('/checkout', name: 'checkout_create', methods: ['POST'])]
    public function createCheckoutSession(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['customerId'])) {
            return $this->json(['error' => 'Le champ customerId est obligatoire.'], Response::HTTP_BAD_REQUEST);
        }
        if (empty($data['cart'])) {
            return $this->json(['error' => 'Le panier ne peut pas être vide.'], Response::HTTP_BAD_REQUEST);
        }

        $customer = (new Customer())->setId($data['customerId']);

        $lineItems = array_map(static fn(array $item): array => [
            'price'    => $item['priceId'],
            'quantity' => (int) ($item['quantity'] ?? 1),
        ], $data['cart']);

        $session = $this->stripeService->createCheckoutSession($customer, $lineItems);

        return $this->json(['clientSecret' => $session->client_secret]);
    }

    #[Route('/payment-links', name: 'payment_link_create', methods: ['POST'])]
    public function createPaymentLink(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['label'])) {
            return $this->json(['error' => 'Le champ label est obligatoire.'], Response::HTTP_BAD_REQUEST);
        }
        if (!isset($data['amount'])) {
            return $this->json(['error' => 'Le champ amount est obligatoire.'], Response::HTTP_BAD_REQUEST);
        }

        $link = $this->stripeService->createPaymentLink(
            $data['label'],
            (int) $data['amount'],
            (int) ($data['quantity'] ?? 1),
        );

        return $this->json(['id' => $link->id, 'url' => $link->url], Response::HTTP_CREATED);
    }

    private function buildAddress(array $data): Address
    {
        return (new Address())
            ->setCity($data['city'] ?? '')
            ->setCountry($data['country'] ?? '')
            ->setLine1($data['line1'] ?? '')
            ->setPostalCode($data['postalCode'] ?? '')
            ->setState($data['state'] ?? '');
    }

    private function serializeCustomer(Customer $customer): array
    {
        return [
            'id'          => $customer->getId(),
            'email'       => $customer->getEmail(),
            'name'        => $customer->getName(),
            'phone'       => $customer->getPhone(),
            'description' => $customer->getDescription(),
        ];
    }

    private function serializeProduct(Product $product): array
    {
        return [
            'id'           => $product->getId(),
            'name'         => $product->getName(),
            'description'  => $product->getDescription(),
            'active'       => $product->isActive(),
            'defaultPrice' => $product->getDefaultPrice(),
        ];
    }
}
