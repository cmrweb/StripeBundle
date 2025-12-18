<?php
namespace Cmrweb\StripeBundle\Enum;

enum InvoiceStatusEnum: string
{
    case DRAFT = 'draft';
    case OPEN = 'open';
    case ACCEPTED = 'accepted';
    case CANCELED = 'canceled';
    case PAID = 'paid';
    case UNCOLLECTIBLE = 'uncollectible';
    case REFUNDED = 'refunded';
}
