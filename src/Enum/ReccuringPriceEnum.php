<?php
namespace Cmrweb\StripeBundle\Enum;

enum ReccuringPriceEnum: string
{
    case DAY = 'day';
    case MONTH = 'month';
    case WEEK = 'week';
    case YEAR = 'year';
}
