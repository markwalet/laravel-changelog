<?php

namespace MarkWalet\Changelog\Exceptions;

use RuntimeException;

class MissingConfigurationException extends RuntimeException
{
    /**
     * NoGitRepositoryException constructor.
     *
     * @param string $key
     */
    public function __construct(string $key)
    {
        $message = "Configuration key `$key` is missing.";
        parent::__construct($message);
    }
}
