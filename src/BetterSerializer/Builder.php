<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer;

use BetterSerializer\DataBind\Converter\Factory\ConverterFactoryInterface;
use BetterSerializer\DataBind\Converter\Factory\ConverterFactory;
use BetterSerializer\DataBind\MetaData\Reader\AnnotationReaderFactory;
use BetterSerializer\DataBind\MetaData\Reader\ClassReader\ClassReader;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Chained\AnnotationCombiner;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Chained\EqualNamesCombiner;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\ConstructorParamsReader;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\ConstructorParamsReaderInterface;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\TypeReader\Chained\DocBlockTypeReader;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\TypeReader\Chained\NativeTypeReader;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\TypeReader as ConstructorParamTypeReader;
use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\PropertiesReader;
use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\PropertiesReaderInterface;
use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeReader\AnnotationPropertyTypeReader;
use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeReader\DocBlockPropertyTypeReader;
use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeReader\TypeReaderInterface;
use BetterSerializer\DataBind\MetaData\Reader\Reader;
use BetterSerializer\DataBind\MetaData\Reader\ReaderInterface as MetaDataReaderInterface;
use BetterSerializer\DataBind\MetaData\Reflection\ReflectionClassHelper;
use BetterSerializer\DataBind\MetaData\Reflection\ReflectionClassHelperInterface;
use BetterSerializer\DataBind\MetaData\Type\Factory\NativeTypeFactory;
use BetterSerializer\DataBind\MetaData\Type\Factory\NativeTypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryBuilder;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\Reader\ReaderInterface;
use BetterSerializer\DataBind\Writer\WriterInterface;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\DocBlockFactoryInterface;
use InvalidArgumentException;
use RuntimeException;

/**
 * Class Builder
 *
 * @author  mfris
 * @package BetterSerializer\DataBind\ObjectMapper
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.TooManyFields)
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
     * @var ClassReader
     */
    private $classMetaDataReader;

    /**
     * @var PropertiesReaderInterface
     */
    private $propertiesMetaDataReader;

    /**
     * @var TypeReaderInterface[]
     */
    private $propertyTypeReaders;

    /**
     * @var ConstructorParamsReaderInterface
     */
    private $constrParamsMetaDataReader;

    /**
     * @var Combiner\PropertyWithConstructorParamCombinerInterface
     */
    private $propertyWithConstrParamCombiner;

    /**
     * @var AnnotationCombiner
     */
    private $chainedAnnotationCombiner;

    /**
     * @var EqualNamesCombiner
     */
    private $chainedEqualNamesCombiner;

    /**
     * @var ConstructorParamTypeReader\TypeReaderInterface
     */
    private $nativeTypeReader;

    /**
     * @var NativeTypeReader
     */
    private $chainedNativeTypeReader;

    /**
     * @var NativeTypeFactoryInterface
     */
    private $nativeTypeFactory;

    /**
     * @var DocBlockTypeReader
     */
    private $chainedDocBlockTypeReader;

    /**
     * @var AnnotationReaderFactory
     */
    private $annotationReaderFactory;

    /**
     * @var ReflectionClassHelperInterface
     */
    private $reflClassHelper;

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
     * @var ConverterFactoryInterface
     */
    private $converterFactory;

    /**
     * @return Serializer
     * @throws InvalidArgumentException
     * @throws RuntimeException
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
     * @throws InvalidArgumentException
     * @throws RuntimeException
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
     * @throws RuntimeException
     */
    private function getReaderBuilder(): ReaderBuilder
    {
        if ($this->readerBuilder === null) {
            $this->readerBuilder = new ReaderBuilder(
                $this->getTypeFactory(),
                $this->getMetaDataReader(),
                $this->getConverterFactory()
            );
        }

        return $this->readerBuilder;
    }

    /**
     * @return WriterInterface
     * @throws InvalidArgumentException
     * @throws RuntimeException
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
     * @throws RuntimeException
     */
    private function getWriterBuilder(): WriterBuilder
    {
        if ($this->writerBuilder === null) {
            $this->writerBuilder = new WriterBuilder($this->getMetaDataReader(), $this->getConverterFactory());
        }

        return $this->writerBuilder;
    }

    /**
     * @return MetaDataReaderInterface
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    private function getMetaDataReader(): MetaDataReaderInterface
    {
        if ($this->metaDataReader === null) {
            $this->metaDataReader = new Reader(
                $this->getClassMetaDataReader(),
                $this->getPropertiesMetaDataReader(),
                $this->getConstrParamsMetaDataReader()
            );
        }

        return $this->metaDataReader;
    }

    /**
     * @return ClassReader
     * @throws InvalidArgumentException
     */
    private function getClassMetaDataReader(): ClassReader
    {
        if ($this->classMetaDataReader === null) {
            $this->classMetaDataReader = new ClassReader($this->getAnnotationReaderFactory()->newAnnotationReader());
        }

        return $this->classMetaDataReader;
    }

    /**
     * @return PropertiesReaderInterface
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    private function getPropertiesMetaDataReader(): PropertiesReaderInterface
    {
        if ($this->propertiesMetaDataReader === null) {
            $this->propertiesMetaDataReader = new PropertiesReader(
                $this->getReflectionClassHelper(),
                $this->getAnnotationReaderFactory()->newAnnotationReader(),
                $this->getTypeFactory(),
                $this->getPropertyTypeReaders()
            );
        }

        return $this->propertiesMetaDataReader;
    }

    /**
     * @return array TypeReaderInterface[]
     */
    private function getPropertyTypeReaders(): array
    {
        if ($this->propertyTypeReaders === null) {
            $this->propertyTypeReaders = [];
            $this->propertyTypeReaders[] = new AnnotationPropertyTypeReader();
            $this->propertyTypeReaders[] = new DocBlockPropertyTypeReader($this->getDocBlockFactory());
        }

        return $this->propertyTypeReaders;
    }

    /**
     * @return ConstructorParamsReaderInterface
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    private function getConstrParamsMetaDataReader(): ConstructorParamsReaderInterface
    {
        if ($this->constrParamsMetaDataReader === null) {
            $this->constrParamsMetaDataReader = new ConstructorParamsReader(
                $this->getPropertyWithConstrParamCombiner(),
                $this->getNativeTypeReader()
            );
        }

        return $this->constrParamsMetaDataReader;
    }

    /**
     * @return Combiner\PropertyWithConstructorParamCombinerInterface
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    private function getPropertyWithConstrParamCombiner(): Combiner\PropertyWithConstructorParamCombinerInterface
    {
        if ($this->propertyWithConstrParamCombiner === null) {
            $this->propertyWithConstrParamCombiner = new Combiner\PropertyWithConstructorParamCombiner([
                $this->getChainedAnnotationCombiner(),
                $this->getChainedEqualNamesCombiner(),
            ]);
        }

        return $this->propertyWithConstrParamCombiner;
    }

    /**
     * @return AnnotationCombiner
     * @throws InvalidArgumentException
     */
    private function getChainedAnnotationCombiner(): AnnotationCombiner
    {
        if ($this->chainedAnnotationCombiner === null) {
            $this->chainedAnnotationCombiner = new AnnotationCombiner(
                $this->getAnnotationReaderFactory()->newAnnotationReader()
            );
        }

        return $this->chainedAnnotationCombiner;
    }

    /**
     * @return EqualNamesCombiner
     */
    private function getChainedEqualNamesCombiner(): EqualNamesCombiner
    {
        if ($this->chainedEqualNamesCombiner === null) {
            $this->chainedEqualNamesCombiner = new EqualNamesCombiner();
        }

        return $this->chainedEqualNamesCombiner;
    }

    /**
     * @return ConstructorParamTypeReader\TypeReaderInterface
     * @throws RuntimeException
     */
    private function getNativeTypeReader(): ConstructorParamTypeReader\TypeReaderInterface
    {
        if ($this->nativeTypeReader === null) {
            $this->nativeTypeReader = new ConstructorParamTypeReader\TypeReader([
                $this->getChainedNativeTypeReader(),
                $this->getChainedDocBlockTypeReader()
            ]);
        }

        return $this->nativeTypeReader;
    }

    /**
     * @return NativeTypeReader
     */
    private function getChainedNativeTypeReader(): NativeTypeReader
    {
        if ($this->chainedNativeTypeReader === null) {
            $this->chainedNativeTypeReader = new NativeTypeReader($this->getNativeTypeFactory());
        }

        return $this->chainedNativeTypeReader;
    }

    /**
     * @return NativeTypeFactoryInterface
     */
    private function getNativeTypeFactory(): NativeTypeFactoryInterface
    {
        if ($this->nativeTypeFactory === null) {
            $this->nativeTypeFactory = new NativeTypeFactory();
        }

        return $this->nativeTypeFactory;
    }

    /**
     * @return DocBlockTypeReader
     */
    private function getChainedDocBlockTypeReader(): DocBlockTypeReader
    {
        if ($this->chainedDocBlockTypeReader === null) {
            $this->chainedDocBlockTypeReader = new DocBlockTypeReader(
                $this->getTypeFactory(),
                $this->getDocBlockFactory()
            );
        }

        return $this->chainedDocBlockTypeReader;
    }

    /**
     * @return AnnotationReaderFactory
     */
    private function getAnnotationReaderFactory(): AnnotationReaderFactory
    {
        if ($this->annotationReaderFactory === null) {
            $this->annotationReaderFactory = new AnnotationReaderFactory();
        }

        return $this->annotationReaderFactory;
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
     * @return ReflectionClassHelperInterface
     */
    private function getReflectionClassHelper(): ReflectionClassHelperInterface
    {
        if ($this->reflClassHelper === null) {
            $this->reflClassHelper = new ReflectionClassHelper();
        }

        return $this->reflClassHelper;
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

    /**
     * @return ConverterFactoryInterface
     */
    private function getConverterFactory(): ConverterFactoryInterface
    {
        if ($this->converterFactory === null) {
            $this->converterFactory = new ConverterFactory();
        }

        return $this->converterFactory;
    }
}
