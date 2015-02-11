<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/5/14
 * Time: 4:45 PM
 */

namespace Iggy\AssetProcessor;

/**
 * Asset Processor Collection
 *
 * @package Iggy\AssetProcessor
 */
class AssetProcessorCollection implements \IteratorAggregate
{
    /**
     * @var AssetProcessorInterface[]
     */
    private $processors;

    // ----------------------------------------------------------------

    /**
     * @param AssetProcessorInterface[] $processors
     */
    public function __construct($processors = [])
    {
        foreach ($processors as $proc) {
            $this->add($proc);
        }
    }

    // ----------------------------------------------------------------

    /**
     * @param AssetProcessorInterface $processor
     */
    public function add(AssetProcessorInterface $processor)
    {
        $this->processors[$processor->getSlug()] = $processor;
    }

    // ----------------------------------------------------------------

    /**
     * @param string $slug
     * @return AssetProcessorInterface
     */
    public function get($slug)
    {
        return $this->processors[$slug];
    }

    // ----------------------------------------------------------------

    /**
     * Has processor?
     *
     * @param string $slug
     * @return bool
     */
    public function has($slug)
    {
        return isset($this->processors[$slug]);
    }

    // ----------------------------------------------------------------

    /**
     * @return AssetProcessorInterface[]
     */
    public function toArray()
    {
        return $this->processors;
    }

    // ----------------------------------------------------------------

    /**
     * @return AssetProcessorInterface[]
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->toArray());
    }
}

/* EOF: AssetProcessorCollection.php */ 