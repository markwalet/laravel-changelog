<?php

namespace MarkWalet\Changelog\Exceptions;

use RuntimeException;

class InvalidXmlException extends RuntimeException
{
    /**
     * NoGitRepositoryException constructor.
     *
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
