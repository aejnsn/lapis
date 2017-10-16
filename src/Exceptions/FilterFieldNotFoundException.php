<?php declare(strict_types=1);

namespace Aejnsn\Lapis\Exceptions;

use Exception;

/**
 * Class FilterFieldNotFoundException
 *
 * @package Aejnsn\Lapis\Exceptions
 */
class FilterFieldNotFoundException extends Exception
{
    /**
     * UndefinedFilterException constructor.
     *
     * @param string $field
     */
    public function __construct($field)
    {
        parent::__construct("Failed to filter by '{$field}'. It may not be configured as a filterable field.");
    }
}
