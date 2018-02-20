* Make `/_templates/*` inaccessible again (maybe as PSR-7 middleware?).  It should return 403.
* Make LESS and SCSS pre-processors fall back to using built-in LESS or SCSS when they are available.
  * Consider making this configurable in a `.iggy.conf` configuration file
* Fix console logging when running as PHP built-in web server 
* Consider updating docs so that router script is different than directory root in PHP built-in webserver (test this),
  so that we don't have to enforce `content/` directory
* Docker image (the docker build file actually pulls the latest iggy from github using URL to built PHAR)
  * The Docker image should use a custom entry point that disallows the arguments/options in the commands
  * Docker should include official LESS and SCSS preprocessors
* Add some useful Twig extensions if you feel like it.
* In the ROBO script, handle updating the version number (ROBO plugin?)
  * Also see if we can use ROBO to build Docker image.
