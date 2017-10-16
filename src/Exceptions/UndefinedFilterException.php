<?php declare(strict_types=1);

namespace Aejnsn\Lapis\Exceptions;

use Exception;

/**
 * Class UndefinedFilterException
 *
 * @package Aejnsn\Lapis\Exceptions
 */
class UndefinedFilterException extends Exception
{
    /**
     * UndefinedFilterException constructor.
     *
     * @param string $modelClass
     */
    public function __construct($modelClass)
    {
        parent::__construct("A filter class has not been defined on the '{$modelClass}' model.");
    }
}
