<?php

declare(strict_types=1);

namespace Custom\Bundle\ContactBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CustomContactBundle extends Bundle
{
    public function getParent()
    {
        return 'OroContactBundle';
    }
}
