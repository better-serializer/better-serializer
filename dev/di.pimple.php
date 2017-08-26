<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader;
use Pimple\Container;

$container = new Container();

$container[BetterSerializer\Serializer::class] = function (Container $c) {
    return new BetterSerializer\Serializer(
        $c[BetterSerializer\DataBind\Reader\ReaderInterface::class],
        $c[BetterSerializer\DataBind\Writer\WriterInterface::class]
    );
};

$container[BetterSerializer\DataBind\Reader\ReaderInterface::class] = function (Container $c) {
    return new BetterSerializer\DataBind\Reader\Reader(
        $c[BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface::class],
        $c[BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryInterface::class],
        $c[BetterSerializer\DataBind\Reader\Context\ContextFactoryInterface::class]
    );
};

$container[BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryInterface::class] =
    function (Container $c) {
        return $c[BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryBuilder::class]->build();
    };

$container[BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryBuilder::class] =
    function (Container $c) {
        return new BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryBuilder(
            $c[BetterSerializer\DataBind\Reader\Converter\ConverterFactoryInterface::class],
            $c[BetterSerializer\DataBind\Reader\Injector\Factory\AbstractFactoryInterface::class],
            $c[BetterSerializer\DataBind\MetaData\Reader\ReaderInterface::class]
        );
    };

$container[BetterSerializer\DataBind\Reader\Injector\Factory\AbstractFactoryInterface::class] = function () {
    return new BetterSerializer\DataBind\Reader\Injector\Factory\AbstractFactory();
};

$container[BetterSerializer\DataBind\Reader\Context\ContextFactoryInterface::class] = function () {
    return new BetterSerializer\DataBind\Reader\Context\ContextFactory();
};

$container[BetterSerializer\DataBind\Writer\WriterInterface::class] = function (Container $c) {
    return new BetterSerializer\DataBind\Writer\Writer(
        $c[BetterSerializer\DataBind\Writer\Type\ExtractorInterface::class],
        $c[BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface::class],
        $c[BetterSerializer\DataBind\Writer\Context\ContextFactoryInterface::class]
    );
};

$container[BetterSerializer\DataBind\Writer\Type\ExtractorInterface::class] = function (Container $c) {
    return $c[BetterSerializer\DataBind\Writer\Type\ExtractorBuilder::class]->build();
};

$container[BetterSerializer\DataBind\Writer\Type\ExtractorBuilder::class] = function () {
    return new BetterSerializer\DataBind\Writer\Type\ExtractorBuilder();
};

$container[BetterSerializer\DataBind\Writer\Context\ContextFactoryInterface::class] = function () {
    return new BetterSerializer\DataBind\Writer\Context\ContextFactory();
};

$container[BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface::class] =
    function (Container $c) {
        return $c[BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryBuilder::class]->build();
    };

$container[BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryBuilder::class] =
    function (Container $c) {
        return new BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryBuilder(
            $c[BetterSerializer\DataBind\Writer\Converter\ConverterFactoryInterface::class],
            $c[BetterSerializer\DataBind\Writer\Extractor\Factory\AbstractFactoryInterface::class],
            $c[BetterSerializer\DataBind\MetaData\Reader\ReaderInterface::class]
        );
    };

$container[BetterSerializer\DataBind\Writer\Converter\ConverterFactoryInterface::class] = function () {
    return new BetterSerializer\DataBind\Writer\Converter\ConverterFactory();
};

$container[BetterSerializer\DataBind\Writer\Extractor\Factory\AbstractFactoryInterface::class] = function () {
    return new BetterSerializer\DataBind\Writer\Extractor\Factory\AbstractFactory();
};

$container[BetterSerializer\DataBind\MetaData\Reader\ReaderInterface::class] = function (Container $c) {
    return new BetterSerializer\DataBind\MetaData\Reader\Reader(
        $c[\BetterSerializer\Reflection\Factory\ReflectionClassFactoryInterface::class],
        $c[BetterSerializer\DataBind\MetaData\Reader\ClassReader\ClassReaderInterface::class],
        $c[BetterSerializer\DataBind\MetaData\Reader\PropertyReader\PropertiesReaderInterface::class],
        $c[BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\ConstructorParamsReaderInterface::class]
    );
};

$container[BetterSerializer\DataBind\MetaData\Reader\ClassReader\ClassReaderInterface::class] =
    function (Container $c) {
        return new BetterSerializer\DataBind\MetaData\Reader\ClassReader\ClassReader(
            $c[Doctrine\Common\Annotations\AnnotationReader::class]
        );
    };

$container[BetterSerializer\DataBind\MetaData\Reader\PropertyReader\PropertiesReaderInterface::class] =
    function (Container $c) {
        $typeReaders = [
            $c[BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeReader\AnnotationPropertyTypeReader::class],
            $c[BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeReader\DocBlockPropertyTypeReader::class],
        ];

        return new BetterSerializer\DataBind\MetaData\Reader\PropertyReader\PropertiesReader(
            $c[Doctrine\Common\Annotations\AnnotationReader::class],
            $c[BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface::class],
            $typeReaders
        );
    };

$container[BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeReader\AnnotationPropertyTypeReader::class] =
    function () {
        return new BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeReader\AnnotationPropertyTypeReader();
    };

$container[BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeReader\DocBlockPropertyTypeReader::class] =
    function (Container $c) {
        return new BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeReader\DocBlockPropertyTypeReader(
            $c[phpDocumentor\Reflection\DocBlockFactoryInterface::class]
        );
    };

$container[BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\ConstructorParamsReaderInterface::class] =
    function (Container $c) {
        return new BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\ConstructorParamsReader(
            $c[ConstructorParamReader\Combiner\PropertyWithConstructorParamCombinerInterface::class],
            $c[BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\TypeReader\TypeReaderInterface::class]
        );
    };

$container[ConstructorParamReader\Combiner\PropertyWithConstructorParamCombinerInterface::class] =
    function (Container $c) {
        return new ConstructorParamReader\Combiner\PropertyWithConstructorParamCombiner([
            $c[ConstructorParamReader\Combiner\Chained\AnnotationCombiner::class],
            $c[ConstructorParamReader\Combiner\Chained\EqualNamesCombiner::class],
        ]);
    };

$container[ConstructorParamReader\Combiner\Chained\AnnotationCombiner::class] = function (Container $c) {
    return new ConstructorParamReader\Combiner\Chained\AnnotationCombiner(
        $c[Doctrine\Common\Annotations\AnnotationReader::class]
    );
};

$container[ConstructorParamReader\Combiner\Chained\EqualNamesCombiner::class] = function () {
    return new BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Chained\EqualNamesCombiner();
};

$container[ConstructorParamReader\TypeReader\TypeReaderInterface::class] = function (Container $c) {
    return new ConstructorParamReader\TypeReader\TypeReader([
        $c[ConstructorParamReader\TypeReader\Chained\NativeTypeReader::class],
        $c[ConstructorParamReader\TypeReader\Chained\DocBlockTypeReader::class]
    ]);
};

$container[ConstructorParamReader\TypeReader\Chained\NativeTypeReader::class] = function (Container $c) {
    return new ConstructorParamReader\TypeReader\Chained\NativeTypeReader(
        $c[BetterSerializer\DataBind\MetaData\Type\Factory\NativeTypeFactoryInterface::class]
    );
};

$container[BetterSerializer\DataBind\MetaData\Type\Factory\NativeTypeFactoryInterface::class] = function () {
    return new BetterSerializer\DataBind\MetaData\Type\Factory\NativeTypeFactory();
};

$container[ConstructorParamReader\TypeReader\Chained\DocBlockTypeReader::class] = function (Container $c) {
    return new ConstructorParamReader\TypeReader\Chained\DocBlockTypeReader(
        $c[BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface::class],
        $c[phpDocumentor\Reflection\DocBlockFactoryInterface::class]
    );
};

$container[Doctrine\Common\Annotations\AnnotationReader::class] = function (Container $c) {
    return $c[BetterSerializer\DataBind\MetaData\Reader\AnnotationReaderFactory::class]->newAnnotationReader();
};

$container[BetterSerializer\DataBind\MetaData\Reader\AnnotationReaderFactory::class] = function () {
    return new BetterSerializer\DataBind\MetaData\Reader\AnnotationReaderFactory();
};

$container[phpDocumentor\Reflection\DocBlockFactoryInterface::class] = function () {
    return phpDocumentor\Reflection\DocBlockFactory::createInstance();
};

$container[BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface::class] = function (Container $c) {
    return $c[BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryBuilder::class]->build();
};

$container[BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryBuilder::class] = function () {
    return new BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryBuilder();
};

$container[BetterSerializer\DataBind\Reader\Converter\ConverterFactoryInterface::class] = function () {
    return new BetterSerializer\DataBind\Reader\Converter\ConverterFactory();
};

$container[BetterSerializer\DataBind\Writer\Converter\ConverterFactoryInterface::class] = function () {
    return new BetterSerializer\DataBind\Writer\Converter\ConverterFactory();
};

$container[\BetterSerializer\Reflection\Factory\ReflectionClassFactoryInterface::class] = function (Container $c) {
    return new \BetterSerializer\Reflection\Factory\ReflectionClassFactory(
        $c[\BetterSerializer\Reflection\UseStatement\UseStatementsExtractorInterface::class]
    );
};

$container[\BetterSerializer\Reflection\UseStatement\UseStatementsExtractorInterface::class] = function (Container $c) {
    return new \BetterSerializer\Reflection\UseStatement\UseStatementsExtractor(
        $c[\BetterSerializer\Reflection\UseStatement\Factory\CodeReaderFactoryInterface::class],
        $c[\BetterSerializer\Reflection\UseStatement\ParserInterface::class]
    );
};

$container[\BetterSerializer\Reflection\UseStatement\Factory\CodeReaderFactoryInterface::class] = function () {
    return new \BetterSerializer\Reflection\UseStatement\Factory\CodeReaderFactory();
};

$container[\BetterSerializer\Reflection\UseStatement\ParserInterface::class] = function () {
    return new \BetterSerializer\Reflection\UseStatement\Parser();
};

return $container;
