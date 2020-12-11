<?php

declare(strict_types=1);

namespace Custom\Bundle\AccountBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CustomAccountBundle extends Bundle
{
    public function getParent()
    {
        return 'OroAccountBundle';
    }
}
