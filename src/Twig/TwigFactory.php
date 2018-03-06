<?php

namespace Iggy\Twig;

use Aptoma\Twig\Extension\MarkdownEngine\PHPLeagueCommonMarkEngine;
use Aptoma\Twig\Extension\MarkdownExtension;
use Iggy\FilePathResolver;
use League\CommonMark\CommonMarkConverter;
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

    /**
     * @var Environment
     */
    private $twig;

    /**
     * TwigFactory constructor.
     *
     * @param array|string[] $paths
     * @param CommonMarkConverter|null $markdownParser
     */
    public function __construct(array $paths, CommonMarkConverter $markdownParser = null)
    {
        $this->twig = new Environment(new TwigFilesystemLoader($paths), [
            'debug'       => true,
            'auto_reload' => true,
            'cache'       => false
        ]);

        // Add Iggy Twig Extension
        $this->twigExtension = new IggyTwigExtension();
        $this->twig->addExtension($this->twigExtension);

        // Add Markdown Engine
        $markdownParser = $markdownParser ?: new CommonMarkConverter();
        $markdownEngine = new PHPLeagueCommonMarkEngine($markdownParser);
        $this->twig->addExtension(new MarkdownExtension($markdownEngine));

        // Add 'markdown_file' Function
        $filePathResolver = new FilePathResolver($paths, [], ['md', 'markdown']);
        $this->twig->addFunction(new \Twig_SimpleFunction('markdown_file', function($path) use ($filePathResolver, $markdownParser) {

            return ($info = $filePathResolver->resolvePath($path))
                ? $markdownParser->convertToHtml(file_get_contents($info->getRealPath()))
                : null;
        }, ['is_safe' => ['html']]));
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