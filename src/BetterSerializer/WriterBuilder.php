<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer;

use BetterSerializer\DataBind\Converter\Factory\ConverterFactoryInterface;
use BetterSerializer\DataBind\MetaData\Reader\ReaderInterface as MetaDataReaderInterface;
use BetterSerializer\DataBind\Writer\Context\ContextFactory;
use BetterSerializer\DataBind\Writer\Context\ContextFactoryInterface;
use BetterSerializer\DataBind\Writer\Extractor\Factory\AbstractFactory;
use BetterSerializer\DataBind\Writer\Extractor\Factory\AbstractFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryBuilder;
use BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Writer\Type\ExtractorBuilder;
use BetterSerializer\DataBind\Writer\Type\ExtractorInterface;
use BetterSerializer\DataBind\Writer\WriterInterface;
use InvalidArgumentException;

/**
 * Class WriterBuilder
 * @author mfris
 * @package BetterSerializer
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
final class WriterBuilder
{

    /**
     * @var MetaDataReaderInterface
     */
    private $metaDataReader;

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
     * @var ConverterFactoryInterface
     */
    private $converterFactory;

    /**
     * @var AbstractFactoryInterface
     */
    private $extractorFactory;

    /**
     * WriterBuilder constructor.
     * @param MetaDataReaderInterface $metaDataReader
     * @param ConverterFactoryInterface $converterFactory
     */
    public function __construct(MetaDataReaderInterface $metaDataReader, ConverterFactoryInterface $converterFactory)
    {
        $this->converterFactory = $converterFactory;
        $this->metaDataReader = $metaDataReader;
    }

    /**
     * @return WriterInterface
     * @throws InvalidArgumentException
     */
    public function getWriter(): WriterInterface
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
                $this->converterFactory,
                $this->getExtractorFactory(),
                $this->metaDataReader
            );
        }

        return $this->processorFactoryBuilder;
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
}
