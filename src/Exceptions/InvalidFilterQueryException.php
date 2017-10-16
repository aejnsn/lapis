<?php

namespace Aejnsn\Lapis\Exceptions;

use Exception;

/**
 * Class InvalidFilterQueryException
 *
 * @package Aejnsn\Lapis\Exceptions
 */
class InvalidFilterQueryException extends Exception
{
    /**
     * InvalidFilterQueryException constructor.
     */
    public function __construct()
    {
        parent::__construct(
            "Filter queries must be specified as an array, e.g., 'http://example.com/api/products?filter[name]=Kindle'."
        );
    }
}
