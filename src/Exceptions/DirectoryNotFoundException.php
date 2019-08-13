<?php

namespace MarkWalet\Changelog\Exceptions;

use RuntimeException;

class DirectoryNotFoundException extends RuntimeException
{
    /**
     * NoGitRepositoryException constructor.
     *
     * @param string $path
     */
    public function __construct($path)
    {
        $message = "Directory `{$path}` is not found.";

        parent::__construct($message);
    }
}
