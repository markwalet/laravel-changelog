<?php

use MarkWalet\Changelog\Formatters\MarkdownChangelogFormatter;
use MarkWalet\Changelog\Formatters\SlackChangelogFormatter;
use MarkWalet\Changelog\Formatters\TextChangelogFormatter;

return [
    /*
    |--------------------------------------------------------------------------
    | Path
    |--------------------------------------------------------------------------
    |
    | The base path determines where all the changes are stored on
    | the filesystem. This is a folder that will contain all
    | the different versions in sub-folders.
    |
    */

    'path' => base_path('.changes'),

    /*
    |--------------------------------------------------------------------------
    | Changelog path
    |--------------------------------------------------------------------------
    |
    | The changelog path determines where the generated changelog
    | markdown file will be stored. Most of the time this is
    | in the root of your repository.
    |
    */

    'changelog_path' => base_path('CHANGELOG.md'),

    /*
    |--------------------------------------------------------------------------
    | Formatters
    |--------------------------------------------------------------------------
    |
    | The formatters output a string based on a given list of releases. These
    | formatters are separated in 2 types. A `text` formatter for CLI output.
    | And a `markdown` formatter for the generation of the changelog file.
    |
    */

    'formatters' => [
        'text' => [
            'driver' => TextChangelogFormatter::class,
            'capitalize' => true,
        ],
        'markdown' => [
            'driver' => MarkdownChangelogFormatter::class,
            'capitalize' => true,
        ],
        'slack' => [
            'driver' => SlackChangelogFormatter::class,
            'capitalize' => true,
        ],
    ],
];
