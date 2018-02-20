<?php

namespace Iggy;
use Symfony\Component\Finder\Finder;
use Twig\Loader\LoaderInterface;
use Twig_Error_Loader;
use Twig_Source;
use Webmozart\PathUtil\Path;

/**
 * Class TwigFilesystemLoader
 * @package Iggy
 */
class TwigFilesystemLoader implements LoaderInterface
{
    const DEFAULT_EXTENSIONS = ['.twig', '.html.twig'];

    /**
     * @var array|string[]
     */
    private $paths;

    /**
     * @var array|string[]
     */
    private $registeredExtensions;

    /**
     * TwigFilesystemLoader constructor.
     *
     * @param array|string[] $paths
     * @param array|string[] $registeredExtensions
     */
    public function __construct(array $paths, array $registeredExtensions = self::DEFAULT_EXTENSIONS)
    {
        $this->paths = $paths;
        $this->registeredExtensions = $registeredExtensions;
    }

    /**
     * Returns the source context for a given template logical name.
     *
     * @param string $name The template logical name
     *
     * @return Twig_Source
     *
     * @throws Twig_Error_Loader When $name is not found
     */
    public function getSourceContext($name)
    {
        if ($templatePath = $this->resolveTemplatePath($name)) {
            return new Twig_Source(file_get_contents($templatePath), $name);
        }
        else {
            throw new Twig_Error_Loader(sprintf('Template %s is not defined', $name));
        }
    }

    /**
     * Gets the cache key to use for the cache for a given template name.
     *
     * @param string $name The name of the template to load
     *
     * @return string The cache key
     *
     * @throws Twig_Error_Loader When $name is not found
     */
    public function getCacheKey($name)
    {
        if ($templatePath = $this->resolveTemplatePath($name)) {
            return $name . ':' . filemtime($templatePath);
        }
        else {
            throw new Twig_Error_Loader(sprintf('Template %s is not defined', $name));
        }
    }

    /**
     * Returns true if the template is still fresh.
     *
     * @param string $name The template name
     * @param int $time Timestamp of the last modification time of the
     *                     cached template
     *
     * @return bool true if the template is fresh, false otherwise
     *
     * @throws Twig_Error_Loader When $name is not found
     */
    public function isFresh($name, $time)
    {
        return filemtime($this->resolveTemplatePath($name)) <= $time;
    }

    /**
     * Check if we have the source code of a template, given its name.
     *
     * @param string $name The name of the template to check if we can load
     *
     * @return bool If the template source code is handled by this loader or not
     */
    public function exists($name)
    {
        return $this->resolveTemplatePath($name) !== null;
    }

    /**
     * Resolve template name
     *
     * @param string $name
     * @return null|string
     */
    private function resolveTemplatePath(string $name): ?string
    {
        foreach ($this->paths as $path) {

            if (is_readable(Path::join($path, $name))) {
                return Path::join($path, $name);
            }

            foreach ($this->registeredExtensions as $extension) {
                $fn = Path::join($path, $name . '.' . ltrim($extension, '.'));
                if (is_readable($fn)) {
                    return $fn;
                }
            }
        }

        // Not found
        return null;
    }
}