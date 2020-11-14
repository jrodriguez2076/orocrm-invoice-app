<?php

namespace Custom\Bundle\InvoiceBundle\Event;

final class Events
{
    const BEFORE_SAVE = 'invoice_files.before_save';

    private function __construct()
    {
    }
}
