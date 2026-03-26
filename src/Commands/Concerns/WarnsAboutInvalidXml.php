<?php

namespace MarkWalet\Changelog\Commands\Concerns;

use MarkWalet\Changelog\Exceptions\InvalidXmlException;

trait WarnsAboutInvalidXml
{
    private function warnAboutInvalidXml(InvalidXmlException $exception): int
    {
        $this->warn($exception->getMessage());

        return self::FAILURE;
    }
}
