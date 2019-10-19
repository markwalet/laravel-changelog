# Release Notes

## [Unreleased](https://github.com/markwalet/laravel-changelog/compare/v1.1.0...master)

### Added
- Added alias command `changelog:added {message}` to quickly add a new added entry
- Added alias command `changelog:changed {message}` to quickly add a new changed entry
- Added alias command `changelog:deprecated {message}` to quickly add a new deprecated entry
- Added alias command `changelog:removed {message}` to quickly add a new removed entry
- Added alias command `changelog:fixed {message}` to quickly add a new fixed entry
- Added alias command `changelog:security {message}` to quickly add a new security entry

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
