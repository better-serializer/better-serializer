<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer;

use BetterSerializer\DataBind\MetaData\Reader\ReaderInterface as MetaDataReaderInterface;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\Reader\Constructor\Factory\ConstructorFactoryInterface;
use BetterSerializer\DataBind\Reader\Constructor\Factory\UnserializeConstructorFactory;
use BetterSerializer\DataBind\Reader\Context\ContextFactory;
use BetterSerializer\DataBind\Reader\Context\ContextFactoryInterface;
use BetterSerializer\DataBind\Reader\Injector\Factory\AbstractFactory as AbstractInjectorFactory;
use BetterSerializer\DataBind\Reader\Injector\Factory\AbstractFactoryInterface as InjectorFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactory;
use BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryBuilder;
use BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Reader\Reader;
use BetterSerializer\DataBind\Reader\ReaderInterface;

/**
 * Class ReaderBuilder
 * @author mfris
 * @package BetterSerializer
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
final class ReaderBuilder
{

    /**
     * @var ReaderInterface
     */
    private $reader;

    /**
     * @var TypeFactoryInterface
     */
    private $typeFactory;

    /**
     * @var MetaDataReaderInterface
     */
    private $metaDataReader;

    /**
     * @var ProcessorFactory
     */
    private $processorFactory;

    /**
     * @var ProcessorFactoryBuilder
     */
    private $processorFactoryBuilder;

    /**
     * @var ConstructorFactoryInterface
     */
    private $constructorFactoryInterface;

    /**
     * @var InjectorFactoryInterface
     */
    private $injectorFactory;

    /**
     * @var ContextFactoryInterface
     */
    private $contextFactory;

    /**
     * ReaderBuilder constructor.
     * @param TypeFactoryInterface $typeFactory
     * @param MetaDataReaderInterface $metaDataReader
     */
    public function __construct(TypeFactoryInterface $typeFactory, MetaDataReaderInterface $metaDataReader)
    {
        $this->typeFactory = $typeFactory;
        $this->metaDataReader = $metaDataReader;
    }

    /**
     * @return ReaderInterface
     */
    public function getReader(): ReaderInterface
    {
        if ($this->reader === null) {
            $this->reader = new Reader($this->typeFactory, $this->getProcessorFactory(), $this->getContextFactory());
        }

        return $this->reader;
    }

    /**
     * @return ProcessorFactoryInterface
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
     */
    private function getProcessorFactoryBuilder(): ProcessorFactoryBuilder
    {
        if ($this->processorFactoryBuilder === null) {
            $this->processorFactoryBuilder = new ProcessorFactoryBuilder(
                $this->getConstructor(),
                $this->getInjectorFactory(),
                $this->metaDataReader
            );
        }

        return $this->processorFactoryBuilder;
    }

    /**
     * @return ConstructorFactoryInterface
     */
    private function getConstructor(): ConstructorFactoryInterface
    {
        if ($this->constructorFactoryInterface === null) {
            $this->constructorFactoryInterface = new UnserializeConstructorFactory();
        }

        return $this->constructorFactoryInterface;
    }

    /**
     * @return InjectorFactoryInterface
     */
    private function getInjectorFactory(): InjectorFactoryInterface
    {
        if ($this->injectorFactory === null) {
            $this->injectorFactory = new AbstractInjectorFactory();
        }

        return $this->injectorFactory;
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
}
