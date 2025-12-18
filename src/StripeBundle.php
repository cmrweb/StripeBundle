<?php
namespace Cmrweb\StripeBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class StripeBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}