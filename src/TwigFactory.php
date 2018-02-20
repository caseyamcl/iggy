<?php

namespace Iggy;

use Psr\Http\Message\RequestInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig_Loader_Array;

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
    public function getTwigEnvironment(RequestInterface $request): Environment
    {
        // Thanks, https://github.com/CouscousPHP/Couscous/
        // But.. this loads all file content into memory for every request.  Very memory inefficient.
        // TODO: Figure out something better.
        /*$finder = new Finder();
        $finder->files()->in($this->paths)->name('*.twig');
        $layouts = [];

        foreach ($finder as $file) {
            /** @var SplFileInfo $file *//*
            $name = $file->getFilename();
            $layouts[$name] = $file->getContents();
        }

        $loader = new Twig_Loader_Array($layouts);*/

        $twig = new Environment(new TwigFilesystemLoader($this->paths), [
            'debug'       => true,
            'auto_reload' => true,
            'cache'       => false
        ]);

        $twigExtension = new IggyTwigExtension($request);
        $twig->addExtension($twigExtension);

        return $twig;
    }
}