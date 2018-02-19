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
     * @var IggyTwigExtension
     */
    private $twigExtension;

    /**
     * TwigFactory constructor.
     *
     * @param array|string[] $paths
     */
    public function __construct(array $paths)
    {
        $this->paths = $paths;
        $this->twig = new Environment(new FilesystemLoader($this->paths), ['debug' => true]);
        $this->twigExtension = new IggyTwigExtension();
        $this->twig->addExtension($this->twigExtension);
    }

    /**
     * @param RequestInterface $request
     * @return Environment
     */
    public function getTwigEnvironment(RequestInterface $request): Environment
    {
        $this->twigExtension->setRequest($request);
        return $this->twig;
    }
}