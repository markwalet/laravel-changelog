<?php

namespace MarkWalet\Changelog\Exceptions;

use InvalidArgumentException as BaseInvalidArgumentException;

class InvalidArgumentException extends BaseInvalidArgumentException
{
    /**
     * NoGitRepositoryException constructor.
     *
     * @param $message
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}