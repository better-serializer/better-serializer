<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer;

use BetterSerializer\DataBind\MetaData\Reader\ReaderFactory;
use BetterSerializer\DataBind\MetaData\Reader\ReaderInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeFactory;
use BetterSerializer\DataBind\MetaData\Type\TypeFactoryInterface;
use BetterSerializer\DataBind\Writer\Context\ContextFactory;
use BetterSerializer\DataBind\Writer\Context\ContextFactoryInterface;
use BetterSerializer\DataBind\Writer\Extractor\Property\Factory\AbstractFactory;
use BetterSerializer\DataBind\Writer\Extractor\Property\Factory\AbstractFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactory;
use BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Writer\WriterInterface;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\DocBlockFactoryInterface;

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
     * @var WriterInterface
     */
    private $writer;

    /**
     * @var ContextFactoryInterface
     */
    private $contextFactory;

    /**
     * @var ProcessorFactoryInterface
     */
    private $processorFactory;

    /**
     * @var ReaderInterface
     */
    private $metaDataReader;

    /**
     * @var ReaderFactory
     */
    private $metaDataReaderFactory;

    /**
     * @var AbstractFactoryInterface
     */
    private $extractorFactory;

    /**
     * @var DocBlockFactoryInterface
     */
    private $docBlockFactory;

    /**
     * @var TypeFactoryInterface
     */
    private $typeFactory;

    /**
     * @return Serializer
     */
    public function createSerializer(): Serializer
    {
        if ($this->serializer === null) {
            $this->serializer = new Serializer($this->getWriter());
        }

        return $this->serializer;
    }

    /**
     * @return WriterInterface
     */
    private function getWriter(): WriterInterface
    {
        if ($this->writer === null) {
            $this->writer = new DataBind\Writer\Writer($this->getProcessorFactory(), $this->getContextFactory());
        }

        return $this->writer;
    }

    /**
     * @return ContextFactoryInterface
     */
    private function getContextFactory(): ContextFactoryInterface
    {
        if ($this->contextFactory === null) {
            $this->contextFactory = new ContextFactory();
        }

        return $this->contextFactory;
    }

    /**
     * @return ProcessorFactoryInterface
     */
    private function getProcessorFactory(): ProcessorFactoryInterface
    {
        if ($this->processorFactory === null) {
            $this->processorFactory = new ProcessorFactory($this->getMetaDataReader(), $this->getExtractorFactory());
        }

        return $this->processorFactory;
    }

    /**
     * @return ReaderInterface
     */
    private function getMetaDataReader(): ReaderInterface
    {
        if ($this->metaDataReader === null) {
            $this->metaDataReader = $this->getMetaDataReaderFactory()->createReader();
        }

        return $this->metaDataReader;
    }

    /**
     * @return AbstractFactoryInterface
     */
    private function getExtractorFactory(): AbstractFactoryInterface
    {
        if ($this->extractorFactory === null) {
            $this->extractorFactory = new AbstractFactory();
        }

        return $this->extractorFactory;
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
            $this->typeFactory = new TypeFactory();
        }

        return $this->typeFactory;
    }
}
