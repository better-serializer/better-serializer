<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

use Pimple\Container;
use BetterSerializer\DataBind\Converter\Factory\ConverterFactoryInterface;
use BetterSerializer\DataBind\Converter\Factory\ConverterFactory;
use BetterSerializer\DataBind\MetaData\Reader\AnnotationReaderFactory;
use BetterSerializer\DataBind\MetaData\Reader\ClassReader\ClassReader;
use BetterSerializer\DataBind\MetaData\Reader\ClassReader\ClassReaderInterface;
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
use BetterSerializer\DataBind\MetaData\Reader\Reader as MetaDataReader;
use BetterSerializer\DataBind\MetaData\Reader\ReaderInterface as MetaDataReaderInterface;
use BetterSerializer\DataBind\MetaData\Reflection\ReflectionClassHelper;
use BetterSerializer\DataBind\MetaData\Reflection\ReflectionClassHelperInterface;
use BetterSerializer\DataBind\MetaData\Type\Factory\NativeTypeFactory;
use BetterSerializer\DataBind\MetaData\Type\Factory\NativeTypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryBuilder;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\Reader\Context\ContextFactory as ReaderContextFactory;
use BetterSerializer\DataBind\Reader\Context\ContextFactoryInterface as ReaderContextFactoryInterface;
use BetterSerializer\DataBind\Reader\Injector\Factory\AbstractFactory as AbstractInjectorFactory;
use BetterSerializer\DataBind\Reader\Injector\Factory\AbstractFactoryInterface as InjectorFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryBuilder as ReaderProcessorFactoryBuilder;
use BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryInterface as ReaderProcessorFactoryInterface;
use BetterSerializer\DataBind\Reader\Reader;
use BetterSerializer\DataBind\Reader\ReaderInterface;
use BetterSerializer\DataBind\Writer\Context\ContextFactory as WriterContextFactory;
use BetterSerializer\DataBind\Writer\Context\ContextFactoryInterface as WriterContextFactoryInterface;
use BetterSerializer\DataBind\Writer\Extractor\Factory\AbstractFactory;
use BetterSerializer\DataBind\Writer\Extractor\Factory\AbstractFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryBuilder as WriterProcessorFactoryBuilder;
use BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface as WriterProcessorFactoryInterface;
use BetterSerializer\DataBind\Writer\Type\ExtractorBuilder;
use BetterSerializer\DataBind\Writer\Type\ExtractorInterface;
use BetterSerializer\DataBind\Writer\Writer;
use BetterSerializer\DataBind\Writer\WriterInterface;
use BetterSerializer\Serializer;
use Doctrine\Common\Annotations\AnnotationReader;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\DocBlockFactoryInterface;

$container = new Container();

$container[Serializer::class] = function (Container $c) {
    return new Serializer(
        $c[ReaderInterface::class],
        $c[WriterInterface::class]
    );
};

$container[ReaderInterface::class] = function (Container $c) {
    return new Reader(
        $c[TypeFactoryInterface::class],
        $c[ReaderProcessorFactoryInterface::class],
        $c[ReaderContextFactoryInterface::class]
    );
};

$container[ReaderProcessorFactoryInterface::class] = function (Container $c) {
    return $c[ReaderProcessorFactoryBuilder::class]->build();
};

$container[ReaderProcessorFactoryBuilder::class] = function (Container $c) {
    return new ReaderProcessorFactoryBuilder(
        $c[ConverterFactoryInterface::class],
        $c[InjectorFactoryInterface::class],
        $c[MetaDataReaderInterface::class]
    );
};

$container[InjectorFactoryInterface::class] = function () {
    return new AbstractInjectorFactory();
};

$container[ReaderContextFactoryInterface::class] = function () {
    return new ReaderContextFactory();
};

$container[WriterInterface::class] = function (Container $c) {
    return new Writer(
        $c[ExtractorInterface::class],
        $c[WriterProcessorFactoryInterface::class],
        $c[WriterContextFactoryInterface::class]
    );
};

$container[ExtractorInterface::class] = function (Container $c) {
    return $c[ExtractorBuilder::class]->build();
};

$container[ExtractorBuilder::class] = function () {
    return new ExtractorBuilder();
};

$container[WriterContextFactoryInterface::class] = function () {
    return new WriterContextFactory();
};

$container[WriterProcessorFactoryInterface::class] = function (Container $c) {
    return $c[WriterProcessorFactoryBuilder::class]->build();
};

$container[WriterProcessorFactoryBuilder::class] = function (Container $c) {
    return new WriterProcessorFactoryBuilder(
        $c[ConverterFactoryInterface::class],
        $c[AbstractFactoryInterface::class],
        $c[MetaDataReaderInterface::class]
    );
};

$container[AbstractFactoryInterface::class] = function () {
    return new AbstractFactory();
};

$container[MetaDataReaderInterface::class] = function (Container $c) {
    return new MetaDataReader(
        $c[ClassReaderInterface::class],
        $c[PropertiesReaderInterface::class],
        $c[ConstructorParamsReaderInterface::class]
    );
};

$container[ClassReaderInterface::class] = function (Container $c) {
    return new ClassReader($c[AnnotationReader::class]);
};

$container[PropertiesReaderInterface::class] = function (Container $c) {
    return new PropertiesReader(
        $c[ReflectionClassHelperInterface::class],
        $c[AnnotationReader::class],
        $c[TypeFactoryInterface::class],
        [
            $c[AnnotationPropertyTypeReader::class],
            $c[DocBlockPropertyTypeReader::class],
        ]
    );
};

$container[AnnotationPropertyTypeReader::class] = function () {
    return new AnnotationPropertyTypeReader();
};

$container[DocBlockPropertyTypeReader::class] = function (Container $c) {
    return new DocBlockPropertyTypeReader($c[DocBlockFactoryInterface::class]);
};

$container[ConstructorParamsReaderInterface::class] = function (Container $c) {
    return new ConstructorParamsReader(
        $c[Combiner\PropertyWithConstructorParamCombinerInterface::class],
        $c[ConstructorParamTypeReader\TypeReaderInterface::class]
    );
};

$container[Combiner\PropertyWithConstructorParamCombinerInterface::class] = function (Container $c) {
    return new Combiner\PropertyWithConstructorParamCombiner([
        $c[AnnotationCombiner::class],
        $c[EqualNamesCombiner::class],
    ]);
};

$container[AnnotationCombiner::class] = function (Container $c) {
    return new AnnotationCombiner($c[AnnotationReader::class]);
};

$container[EqualNamesCombiner::class] = function () {
    return new EqualNamesCombiner();
};

$container[ConstructorParamTypeReader\TypeReaderInterface::class] = function (Container $c) {
    return new ConstructorParamTypeReader\TypeReader([
        $c[NativeTypeReader::class],
        $c[DocBlockTypeReader::class]
    ]);
};

$container[NativeTypeReader::class] = function (Container $c) {
    return new NativeTypeReader($c[NativeTypeFactoryInterface::class]);
};

$container[NativeTypeFactoryInterface::class] = function () {
    return new NativeTypeFactory();
};

$container[DocBlockTypeReader::class] = function (Container $c) {
    return new DocBlockTypeReader(
        $c[TypeFactoryInterface::class],
        $c[DocBlockFactoryInterface::class]
    );
};

$container[AnnotationReader::class] = function (Container $c) {
    return $c[AnnotationReaderFactory::class]->newAnnotationReader();
};

$container[AnnotationReaderFactory::class] = function () {
    return new AnnotationReaderFactory();
};

$container[DocBlockFactoryInterface::class] = function () {
    return DocBlockFactory::createInstance();
};

$container[ReflectionClassHelperInterface::class] = function () {
    return new ReflectionClassHelper();
};

$container[TypeFactoryInterface::class] = function (Container $c) {
    return $c[TypeFactoryBuilder::class]->build();
};

$container[TypeFactoryBuilder::class] = function () {
    return new TypeFactoryBuilder();
};

$container[ConverterFactoryInterface::class] = function () {
    return new ConverterFactory();
};

return $container;
