<?php

namespace Iggy;

use Psr\Http\Message\RequestInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Class TwigFactory
 * @package Iggy
 */
class TwigFactory
{
    /**
     * @var array|string[]
     */
    private $paths;

    /**
     * TwigFactory constructor.
     *
     * @param array|string[] $paths
     */
    public function __construct(array $paths)
    {
        $this->paths = $paths;
    }

    /**
     * @param RequestInterface $request
     * @return Environment
     */
    public function buildTwig(RequestInterface $request): Environment
    {
        $twig = new Environment(new FilesystemLoader($this->paths), ['debug' => true]);
        $twig->addExtension(new IggyTwigExtension($request));
        return $twig;
    }
}