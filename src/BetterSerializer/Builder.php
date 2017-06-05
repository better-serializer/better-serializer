<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer;

use BetterSerializer\DataBind\MetaData\Reader\ReaderFactory;
use BetterSerializer\DataBind\MetaData\Reader\ReaderInterface as MetaDataReaderInterface;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryBuilder;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\Reader\ReaderInterface;
use BetterSerializer\DataBind\Writer\WriterInterface;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\DocBlockFactoryInterface;
use InvalidArgumentException;

/**
 * Class Builder
 *
 * @author  mfris
 * @package BetterSerializer\DataBind\ObjectMapper
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
final class Builder
{

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var ReaderInterface
     */
    private $reader;

    /**
     * @var ReaderBuilder
     */
    private $readerBuilder;

    /**
     * @var WriterInterface
     */
    private $writer;

    /**
     * @var WriterBuilder
     */
    private $writerBuilder;

    /**
     * @var MetaDataReaderInterface
     */
    private $metaDataReader;

    /**
     * @var ReaderFactory
     */
    private $metaDataReaderFactory;

    /**
     * @var TypeFactoryInterface
     */
    private $typeFactory;

    /**
     * @var TypeFactoryBuilder
     */
    private $typeFactoryBuilder;

    /**
     * @var DocBlockFactoryInterface
     */
    private $docBlockFactory;

    /**
     * @return Serializer
     * @throws InvalidArgumentException
     */
    public function createSerializer(): Serializer
    {
        if ($this->serializer === null) {
            $this->serializer = new Serializer($this->getReader(), $this->getWriter());
        }

        return $this->serializer;
    }

    /**
     * @return ReaderInterface
     */
    private function getReader(): ReaderInterface
    {
        if ($this->reader === null) {
            $this->reader = $this->getReaderBuilder()->getReader();
        }

        return $this->reader;
    }

    /**
     * @return ReaderBuilder
     * @throws InvalidArgumentException
     */
    private function getReaderBuilder(): ReaderBuilder
    {
        if ($this->readerBuilder === null) {
            $this->readerBuilder = new ReaderBuilder($this->getTypeFactory(), $this->getMetaDataReader());
        }

        return $this->readerBuilder;
    }

    /**
     * @return WriterInterface
     * @throws InvalidArgumentException
     */
    private function getWriter(): WriterInterface
    {
        if ($this->writer === null) {
            $this->writer = $this->getWriterBuilder()->getWriter();
        }

        return $this->writer;
    }

    /**
     * @return WriterBuilder
     * @throws InvalidArgumentException
     */
    private function getWriterBuilder(): WriterBuilder
    {
        if ($this->writerBuilder === null) {
            $this->writerBuilder = new WriterBuilder($this->getMetaDataReader());
        }

        return $this->writerBuilder;
    }

    /**
     * @return MetaDataReaderInterface
     * @throws InvalidArgumentException
     */
    private function getMetaDataReader(): MetaDataReaderInterface
    {
        if ($this->metaDataReader === null) {
            $this->metaDataReader = $this->getMetaDataReaderFactory()->createReader();
        }

        return $this->metaDataReader;
    }

    /**
     * @return ReaderFactory
     */
    private function getMetaDataReaderFactory(): ReaderFactory
    {
        if ($this->metaDataReaderFactory === null) {
            $this->metaDataReaderFactory = new ReaderFactory($this->getDocBlockFactory(), $this->getTypeFactory());
        }

        return $this->metaDataReaderFactory;
    }

    /**
     * @return DocBlockFactoryInterface
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function getDocBlockFactory(): DocBlockFactoryInterface
    {
        if ($this->docBlockFactory === null) {
            $this->docBlockFactory = DocBlockFactory::createInstance();
        }

        return $this->docBlockFactory;
    }

    /**
     * @return TypeFactoryInterface
     */
    private function getTypeFactory(): TypeFactoryInterface
    {
        if ($this->typeFactory === null) {
            $this->typeFactory = $this->getTypeFactoryBuilder()->build();
        }

        return $this->typeFactory;
    }

    /**
     * @return TypeFactoryBuilder
     */
    private function getTypeFactoryBuilder(): TypeFactoryBuilder
    {
        if ($this->typeFactoryBuilder === null) {
            $this->typeFactoryBuilder = new TypeFactoryBuilder();
        }

        return $this->typeFactoryBuilder;
    }
}
