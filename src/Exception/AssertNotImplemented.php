<?php

namespace Exbil\Mailcow\Exception;

use Exception;

class AssertNotImplemented extends Exception
{
    public function __construct()
    {
        parent::__construct('This function does not exist in the Sandbox! It will be added Soon!', 0, null);
    }
}