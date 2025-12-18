<?php
namespace Cmrweb\StripeBundle\Enum;

enum CouponDurationEnum:string {
    case FOREVER = 'forever';
    case ONCE = 'once';
    case REPEATING = 'repeating';
}