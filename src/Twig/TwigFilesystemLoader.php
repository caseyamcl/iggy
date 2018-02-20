<?php

namespace Iggy\Twig;

/**
 * Class TwigFilesystemLoader2
 * @package Iggy
 */
class TwigFilesystemLoader extends \Twig_Loader_Filesystem
{
    /**
     * @param $name
     * @return bool|false|string
     * @throws \Twig_Error_Loader
     */
    public function getCacheKey($name)
    {
        $templateInfo = $this->findTemplate($name, false);
        $suffix =  ($templateInfo) ? '_' . filemtime($templateInfo) : '';
        return parent::getCacheKey($name) . $suffix;
    }

}