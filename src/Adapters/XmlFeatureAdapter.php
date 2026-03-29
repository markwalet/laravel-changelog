<?php

namespace MarkWalet\Changelog\Adapters;

use DOMDocument;
use Illuminate\Contracts\Filesystem\FileNotFoundException as FilesystemFileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use LibXMLError;
use MarkWalet\Changelog\Change;
use MarkWalet\Changelog\Exceptions\FileNotFoundException;
use MarkWalet\Changelog\Exceptions\InvalidXmlException;
use MarkWalet\Changelog\Feature;

class XmlFeatureAdapter implements FeatureAdapter
{
    /**
     * @var Filesystem
     */
    private Filesystem $filesystem;

    /**
     * XmlFeatureAdapter constructor.
     */
    public function __construct()
    {
        $this->filesystem = new Filesystem;
    }

    /**
     * Load a feature.
     *
     * @param string $path
     * @return Feature
     */
    public function read(string $path): Feature
    {
        try {
            $content = $this->filesystem->get($path);
        } catch (FilesystemFileNotFoundException $e) {
            throw new FileNotFoundException($path);
        }

        $previousValue = libxml_use_internal_errors(true);
        $element = simplexml_load_string($content);
        if ($element === false) {
            $error = collect(libxml_get_errors())
                ->map(fn (LibXMLError $error) => trim($error->message))
                ->filter()
                ->implode(', ');

            libxml_clear_errors();
            libxml_use_internal_errors($previousValue);

            $message = "Invalid xml in \"$path\".";
            if ($error !== '') {
                $message = "Invalid xml in \"$path\": $error";
            }

            throw new InvalidXmlException($message);
        }
        libxml_clear_errors();
        libxml_use_internal_errors($previousValue);

        $feature = new Feature;

        foreach ($element->children() as $change) {
            $type = $change->attributes()['type'];
            if (is_null($type)) {
                throw new InvalidXmlException("Invalid xml in \"$path\": Missing `type` attribute on change element.");
            }
            $message = (string) $change;

            $feature->add(new Change($type, $message));
        }

        return $feature;
    }

    /**
     * Store a feature.
     *
     * @param string $path
     * @param Feature $feature
     */
    public function write(string $path, Feature $feature): void
    {
        $xml = new DOMDocument;
        $xml->formatOutput = true;
        $xml->encoding = 'UTF-8';

        $root = $xml->createElement('feature');

        foreach ($feature->changes() as $change) {
            $element = $xml->createElement('change', $change->message());
            $element->setAttribute('type', $change->type());
            $root->appendChild($element);
        }

        $xml->appendChild($root);
        $output = $xml->saveXML();

        $directory = dirname($path);
        if ($this->filesystem->isDirectory($directory) === false) {
            $this->filesystem->makeDirectory($directory, 0755, true);
        }

        $this->filesystem->put($path, $output);
    }

    /**
     * Check if there is an existing feature on the given path.
     *
     * @param string $path
     * @return bool
     */
    public function exists(string $path): bool
    {
        return $this->filesystem->exists($path) && $this->filesystem->isReadable($path);
    }
}
