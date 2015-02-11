<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/6/14
 * Time: 11:44 AM
 */

namespace Iggy\AssetProcessor;


use Iggy\HttpException;
use Skyzyx\Components\Mimetypes\Mimetypes;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileProcessor implements AssetProcessorInterface
{
    /**
     * @var Mimetypes
     */
    private $mimeTypes;

    // ----------------------------------------------------------------

    /**
     * Constructor
     *
     * @param Mimetypes $mimeTypes
     */
    public function __construct(Mimetypes $mimeTypes = null)
    {
        $this->mimeTypes = $mimeTypes ?: MimeTypes::getInstance();
    }


    // ----------------------------------------------------------------

    /**
     * Load Asset
     *
     * @param $path
     * @throws \Iggy\HttpException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function load($path)
    {
        if ( ! is_file($path)) {
            throw new HttpException(404, "Asset not exists or is not a file");
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mime = $this->mimeTypes->fromExtension($ext) ?: 'application/octet-stream';

        $streamer = function() use ($path) {
            readfile($path);
        };

        return new StreamedResponse($streamer, 200, ['Content-type' => $mime]);
    }

    // ----------------------------------------------------------------

    /**
     * Get the function name for use in template layer
     *
     * @return string
     */
    public function getSlug()
    {
        return 'asset'; // generic asset
    }
}

/* EOF: FileProcessor.php */ 