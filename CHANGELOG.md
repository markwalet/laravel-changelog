# Release Notes

## [Unreleased](https://github.com/markwalet/laravel-changelog/compare/v1.10.0...master)

### Added
- Support Laravel 12
- Included PHP 8.4 in test matrix

## [v1.10.0 (2024-03-13)](https://github.com/markwalet/laravel-changelog/compare/v1.9.0...v1.10.0)

### Added
- Added support for Laravel 11

### Removed
- Removed support for PHP 8.0
- Removed support for Laravel 7
- Removed support for Laravel 8
- Removed support for Laravel 9

### Changed
- Upgraded to PHPUnit 10.

## [v1.9.0 (2023-12-02)](https://github.com/markwalet/laravel-changelog/compare/v1.8.0...v1.9.0)

### Added
- Added support for PHP 8.3.
- Added dependabot integration

### Fixed
- Reduced amount of checks when creating pull requests.

## [v1.8.0 (2023-02-17)](https://github.com/markwalet/laravel-changelog/compare/v1.7.0...v1.8.0)

### Added
- Added support for Laravel 10
- Added support for PHP 8.2

### Removed
- Removed support for PHP 7.4
- Removed support for Laravel 6

## [v1.7.0 (2022-04-21)](https://github.com/markwalet/laravel-changelog/compare/v1.6.1...v1.7.0)

## Added
- Make sure the unreleased folder stays in the filesystem for better diff comparisons ([#38](https://github.com/markwalet/laravel-changelog/pull/38))
- Add a `changelog:install` artisan command. ([#38](https://github.com/markwalet/laravel-changelog/pull/38))
- Add a `changelog:current` artisan command. ([#42](https://github.com/markwalet/laravel-changelog/pull/42))

## Fixed
- Made all command output show in a consistent manner. ([#43](https://github.com/markwalet/laravel-changelog/pull/43))

## [v1.6.1 (2022-03-27)](https://github.com/markwalet/laravel-changelog/compare/v1.6.0...v1.6.1)

### Fixed
- Fixed support for nested branch names ([#35](https://github.com/markwalet/laravel-changelog/pull/35))

## [v1.6.0 (2021-12-30)](https://github.com/markwalet/laravel-changelog/compare/v1.5.0...v1.6.0)

### Added
- Added Laravel 9 support ([#32](https://github.com/markwalet/laravel-changelog/pull/32))

## [v1.5.0 (2021-12-30)](https://github.com/markwalet/laravel-changelog/compare/v1.4.1...v1.5.0)

## Added
- Added PHP 8.0 support
- Added PHP 8.1 support ([#31](https://github.com/markwalet/laravel-changelog/pull/31))

## Removed
- Removed PHP 7.2 support
- Removed PHP 7.3 support

### Fixed
- Fixed recursive folder creation ([#27](https://github.com/markwalet/laravel-changelog/pull/27))

## [v1.4.1 (2021-04-19)](https://github.com/markwalet/laravel-changelog/compare/v1.4.0...v1.4.1)

### Added
- Added Laravel 8 support. ([#25](https://github.com/markwalet/laravel-changelog/pull/25))

## [v1.4.0 (2020-06-22)](https://github.com/markwalet/laravel-changelog/compare/v1.3.1...v1.4.0)

### Added
- Added a Slack formatter ([#22](https://github.com/markwalet/laravel-changelog/issues/22))

## [v1.3.1 (2020-03-25)](https://github.com/markwalet/laravel-changelog/compare/v1.3.0...v1.3.1)

### Changed
- Updated build status badge in readme.

## [v1.3.0 (2020-03-25)](https://github.com/markwalet/laravel-changelog/compare/v1.2.0...v1.3.0)

### Added
- Added Github Actions integration.
- Added PHP 7.4 support.
- Added a `.gitattributes` file to shrink down releases.
 
### Removed
- Removed support for Laravel 5.
- Removed support for PHP 7.1
- Removed Travis integration.

## [v1.2.0 (2020-03-19)](https://github.com/markwalet/laravel-changelog/compare/v1.1.1...v1.2.0)

### Added
- Add support for Laravel 7. ([#19](https://github.com/markwalet/laravel-changelog/issues/19))

## [v1.1.1 (2019-10-17)](https://github.com/markwalet/laravel-changelog/compare/v1.1.0...v1.1.1)

### Fixed
- Fixed minimum stability issues.

## [v1.1.0 (2019-10-10)](https://github.com/markwalet/laravel-changelog/compare/v1.0.2...v1.1.0)

### Added
- Added optional capitalization for version names in generated changelog ([#5](https://github.com/markwalet/laravel-changelog/issues/5))
- Added Codecov integration. ([#9](https://github.com/markwalet/laravel-changelog/issues/9))
- Added StyleCI integration. ([#11](https://github.com/markwalet/laravel-changelog/issues/11))
- Added Laravel 6 support. ([#7](https://github.com/markwalet/laravel-changelog/issues/7))

### Removed
- Removed Coveralls integration. ([#9](https://github.com/markwalet/laravel-changelog/issues/9))

## [v1.0.2 (2019-09-29)](https://github.com/markwalet/laravel-changelog/compare/v1.0.1...v1.0.2)

### Fixed
- Fixed a code example in the readme ([#1](https://github.com/markwalet/laravel-changelog/issues/1))
- Fixed the publishing path for the template view ([#3](https://github.com/markwalet/laravel-changelog/issues/3))

## [v1.0.1 (2019-09-13)](https://github.com/markwalet/laravel-changelog/compare/v1.0.0...v1.0.1)

### Changed
 - Convert all change types to lowercase before write
