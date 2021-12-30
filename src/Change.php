<?php

namespace MarkWalet\Changelog;

class Change
{
    /** @var string */
    private string $type;

    /** @var string */
    private string $message;

    /**
     * Change constructor.
     *
     * @param string $type
     * @param string $message
     */
    public function __construct(string $type, string $message)
    {
        $this->type = strtolower($type);
        $this->message = $message;
    }

    /**
     * Get the type of the change.
     *
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * Get the message of the change.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->message;
    }
}
