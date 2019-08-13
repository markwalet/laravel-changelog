<?php

namespace MarkWalet\Changelog\Exceptions;

use RuntimeException;

class VersionAlreadyExistsException extends RuntimeException
{
    /**
     * @var string
     */
    private $version;

    /**
     * NoGitRepositoryException constructor.
     *
     * @param string $version
     */
    public function __construct(string $version)
    {
        $this->version = $version;

        parent::__construct("Version `{$version}` already exists.");
    }

    /**
     * Get the version for this exception.
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }
}
