<?php

namespace MarkWalet\Changelog\Exceptions;

use RuntimeException;

class FileNotFoundException extends RuntimeException
{
    /**
     * NoGitRepositoryException constructor.
     *
     * @param string $path
     */
    public function __construct($path)
    {
        $message = "File `{$path}` is not found.";

        parent::__construct($message);
    }
}
