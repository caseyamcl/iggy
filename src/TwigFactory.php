<?php

namespace Iggy;

use Psr\Http\Message\RequestInterface;
use Twig\Environment;

/**
 * Class TwigFactory
 * @package Iggy
 */
class TwigFactory
{
    /**
     * @var \Twig_LoaderInterface
     */
    private $loader;

    /**
     * TwigFactory constructor.
     *
     * @param \Twig_LoaderInterface $loader  Twig Loader
     */
    public function __construct(\Twig_LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     * @param RequestInterface $request
     * @return Environment
     */
    public function buildTwig(RequestInterface $request): Environment
    {
        $twig = new Environment($this->loader);
        $twig->addExtension(new IggyTwigExtension($request));
        return $twig;
    }
}