<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer;

use BetterSerializer\DataBind\MetaData\Reader\ReaderFactory;
use BetterSerializer\DataBind\MetaData\Reader\ReaderInterface;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryBuilder;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\Context\ContextFactory;
use BetterSerializer\DataBind\Context\ContextFactoryInterface;
use BetterSerializer\DataBind\Writer\Extractor\Factory\AbstractFactory;
use BetterSerializer\DataBind\Writer\Extractor\Factory\AbstractFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryBuilder;
use BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Writer\Type\ExtractorBuilder;
use BetterSerializer\DataBind\Writer\Type\ExtractorInterface;
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
     * @var WriterInterface
     */
    private $writer;

    /**
     * @var ExtractorBuilder
     */
    private $typeExtractorBuilder;

    /**
     * @var ExtractorInterface
     */
    private $typeExtractor;

    /**
     * @var ContextFactoryInterface
     */
    private $contextFactory;

    /**
     * @var ProcessorFactoryInterface
     */
    private $processorFactory;

    /**
     * @var ProcessorFactoryBuilder
     */
    private $processorFactoryBuilder;

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
     * @var TypeFactoryBuilder
     */
    private $typeFactoryBuilder;

    /**
     * @return Serializer
     * @throws InvalidArgumentException
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
     * @throws InvalidArgumentException
     */
    private function getWriter(): WriterInterface
    {
        if ($this->writer === null) {
            $this->writer = new DataBind\Writer\Writer(
                $this->getTypeExtractor(),
                $this->getProcessorFactory(),
                $this->getContextFactory()
            );
        }

        return $this->writer;
    }

    /**
     * @return ExtractorInterface
     */
    private function getTypeExtractor(): ExtractorInterface
    {
        if ($this->typeExtractor === null) {
            $this->typeExtractor = $this->getTypeExtractorBuilder()->build();
        }

        return $this->typeExtractor;
    }

    /**
     * @return ExtractorBuilder
     */
    private function getTypeExtractorBuilder(): ExtractorBuilder
    {
        if ($this->typeExtractorBuilder === null) {
            $this->typeExtractorBuilder = new ExtractorBuilder();
        }

        return $this->typeExtractorBuilder;
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
     * @throws InvalidArgumentException
     */
    private function getProcessorFactory(): ProcessorFactoryInterface
    {
        if ($this->processorFactory === null) {
            $this->processorFactory = $this->getProcessorFactoryBuilder()->build();
        }

        return $this->processorFactory;
    }

    /**
     * @return ProcessorFactoryBuilder
     * @throws InvalidArgumentException
     */
    private function getProcessorFactoryBuilder(): ProcessorFactoryBuilder
    {
        if ($this->processorFactoryBuilder === null) {
            $this->processorFactoryBuilder = new ProcessorFactoryBuilder(
                $this->getExtractorFactory(),
                $this->getMetaDataReader()
            );
        }

        return $this->processorFactoryBuilder;
    }

    /**
     * @return ReaderInterface
     * @throws InvalidArgumentException
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
