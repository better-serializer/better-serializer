<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer;

use BetterSerializer\DataBind\Writer\WriterInterface;
use BetterSerializer\Common\SerializationType;
use LogicException;
use ReflectionException;
use RuntimeException;

/**
 * Class Serializer
 *
 * @author  mfris
 * @package BetterSerializer
 */
final class Serializer
{

    /**
     * @var WriterInterface
     */
    private $writer;

    /**
     * ObjectMapper constructor.
     *
     * @param WriterInterface $writer
     */
    public function __construct(WriterInterface $writer)
    {
        $this->writer = $writer;
    }

    /**
     * @param mixed             $data
     * @param SerializationType $type
     * @return string
     * @throws LogicException
     * @throws ReflectionException
     * @throws RuntimeException
     */
    public function writeValueAsString($data, SerializationType $type): string
    {
        return $this->writer->writeValueAsString($data, $type);
    }
}
