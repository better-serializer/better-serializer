<?php
/**
 * @author  mfris
 */
namespace BetterSerializer\DataBind;

use BetterSerializer\Common\SerializationType;

/**
 * Class Jackson
 * @author mfris
 * @package BetterSerializer
 */
final class ObjectMapper
{

    /**
     * @var Writer
     */
    private $writer;

    /**
     * ObjectMapper constructor.
     * @param Writer $writer
     */
    public function __construct(Writer $writer)
    {
        $this->writer = $writer;
    }

    /**
     * @param object $object
     * @param SerializationType $type
     * @return string
     */
    public function writeValueAsString($object, SerializationType $type): string
    {
        return 'test';
    }
}
