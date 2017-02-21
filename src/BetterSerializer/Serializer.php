<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer;

use BetterSerializer\DataBind\Writer;
use BetterSerializer\Common\SerializationType;

/**
 * Class Jackson
 *
 * @author  mfris
 * @package BetterSerializer
 */
final class Serializer
{

    /**
     * @var Writer
     */
    private $writer;

    /**
     * ObjectMapper constructor.
     *
     * @param Writer $writer
     */
    public function __construct(Writer $writer)
    {
        $this->writer = $writer;
    }

    /**
     * @param Object            $object
     * @param SerializationType $type
     * @return string
     * @SuppressWarnings(PHPMD) // temporary
     */
    public function writeValueAsString($object, SerializationType $type): string
    {
        return 'test';
    }
}
