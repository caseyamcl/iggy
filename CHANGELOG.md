# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## Version 1.0 (unreleased)
### Changed
- Iggy now requires PHP7.1+
- Refactored entire app to work as a reusable PHAR
- Replaced Symfony foundation with React HTTP server
- Updated LESS CSS and SASS preprocessors (now we use built-in LESS and SASS when available)
- Replaced bldr with Robo for PHP for building Phar
- Upgraded to Twig 2.0
- Updated documentation and CHANGELOG to reflect new installation and use paradigm
- Brought code up to PSR-2 standards

### Added
- Commands for initializing running Iggy: `init` and `serve`
- Docker support and instructions for using Docker to run Iggy

### Removed
- Removed jSQueeze processor (not overly useful for development) 

## Version 0.6 (2015 Feb 11)
### Added
- Added `.gitattributes`
- Added bldr configuration file
- Added `InstallIggy` and Composer `create-project` hook

### Changed
- Changed to PSR-4 Autloader Standard
- Changed file structure in source app around to prepare for PHAR deployment with bldr
- Changed installation procedure and documentation

## Version 0.5.1 (2015 Feb 11)
- Improved documentation

## Version 0.5 (2014 Dec 15)
- Initial Release 
