<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\MetaData;

use BetterSerializer\DataBind\MetaData\Model\MetaDataInterface;
use BetterSerializer\DataBind\MetaData\Reader\ReaderInterface;
use BetterSerializer\DataBind\Writer\SerializationContextInterface;
use LogicException;
use ReflectionException;

/**
 * Class ContextualReader
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 */
final class ContextualReader implements ContextualReaderInterface
{

    /**
     * @var ReaderInterface
     */
    private $reader;

    /**
     * ContextualReader constructor.
     * @param ReaderInterface $reader
     */
    public function __construct(ReaderInterface $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @param string $className
     * @param SerializationContextInterface $context
     * @return MetaDataInterface
     * @throws LogicException | ReflectionException
     */
    public function read(string $className, SerializationContextInterface $context): MetaDataInterface
    {
        $nestedMetaData = $this->reader->read($className);

        return new ContextualMetaData($nestedMetaData, $context);
    }
}
