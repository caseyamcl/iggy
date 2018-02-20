<?php

namespace Iggy\Twig;

use Psr\Http\Message\RequestInterface;
use Twig\Environment;

/**
 * Class TwigFactory
 * @package Iggy
 */
class TwigFactory
{
    /**
     * @var IggyTwigExtension
     */
    private $twigExtension;

    private $twig;

    /**
     * TwigFactory constructor.
     *
     * @param array|string[] $paths
     */
    public function __construct(array $paths)
    {
        $this->twig = new Environment(new TwigFilesystemLoader($paths), [
            'debug'       => true,
            'auto_reload' => true,
            'cache'       => false
        ]);
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