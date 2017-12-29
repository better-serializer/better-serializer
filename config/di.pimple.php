<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader;
use BetterSerializer\DataBind\MetaData\Type\Factory\Chain as TypeFactoryChain;
use BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard\ParamProcessor;
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

$container[BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactory::class] =
    function () {
        return new BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactory();
    };

$container[BetterSerializer\DataBind\Reader\Processor\Factory\RecursiveProcessorFactory::class] =
    function (Container $c) {
        return new BetterSerializer\DataBind\Reader\Processor\Factory\RecursiveProcessorFactory(
            $c[BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactory::class]
        );
    };

$container[BetterSerializer\DataBind\Reader\Processor\Factory\CachedProcessorFactory::class] =
    function (Container $c) {
        return new BetterSerializer\DataBind\Reader\Processor\Factory\CachedProcessorFactory(
            $c[BetterSerializer\DataBind\Reader\Processor\Factory\RecursiveProcessorFactory::class],
            $c[Doctrine\Common\Cache\Cache::class]
        );
    };

$container['BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryInterface::Unitialized'] =
    function (Container $c) {
        return $c[BetterSerializer\DataBind\Reader\Processor\Factory\CachedProcessorFactory::class];
    };

$container[BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryInterface::class] =
    function (Container $c) {
        $factory = $c['BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryInterface::Unitialized'];

        $factory->addMetaDataChainMember(
            $c[BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain\SimplePropertyMember::class]
        );
        $factory->addMetaDataChainMember(
            $c[BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain\ComplexPropertyMember::class]
        );
        $factory->addTypeChainMember(
            $c[BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain\CollectionMember::class]
        );
        $factory->addTypeChainMember(
            $c[BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain\ObjectMember::class]
        );
        $factory->addTypeChainMember(
            $c[BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain\SimpleMember::class]
        );
        $factory->addTypeChainMember(
            $c[BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain\ExtensionMember::class]
        );
        $factory->addTypeChainMember(
            $c[BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain\ExtensionCollectionMember::class]
        );

        return $factory;
    };

$container[BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain\SimplePropertyMember::class] =
    function (Container $c) {
        return new BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain\SimplePropertyMember(
            $c[BetterSerializer\DataBind\Reader\Converter\ConverterFactoryInterface::class],
            $c[BetterSerializer\DataBind\Reader\Injector\Factory\AbstractFactoryInterface::class]
        );
    };

$container[BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain\ComplexPropertyMember::class] =
    function (Container $c) {
        return new BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain\ComplexPropertyMember(
            $c['BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryInterface::Unitialized'],
            $c[BetterSerializer\DataBind\Reader\Injector\Factory\AbstractFactoryInterface::class]
        );
    };

$container[BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain\CollectionMember::class] =
    function (Container $c) {
        return new BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain\CollectionMember(
            $c[BetterSerializer\DataBind\Reader\Converter\ConverterFactoryInterface::class],
            $c['BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryInterface::Unitialized']
        );
    };

$container[BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain\ObjectMember::class] =
    function (Container $c) {
        return new BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain\ObjectMember(
            $c['BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryInterface::Unitialized'],
            $c[BetterSerializer\DataBind\Reader\Instantiator\Factory\InstantiatorFactoryInterface::class],
            $c[BetterSerializer\DataBind\MetaData\Reader\ReaderInterface::class]
        );
    };

$container[BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain\SimpleMember::class] =
    function (Container $c) {
        return new BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain\SimpleMember(
            $c[BetterSerializer\DataBind\Reader\Converter\ConverterFactoryInterface::class]
        );
    };

$container[BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain\ExtensionMember::class] = function () {
    return new BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain\ExtensionMember();
};

$container[BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain\ExtensionCollectionMember::class] =
    function (Container $c) {
        return new BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain\ExtensionCollectionMember(
            $c['BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryInterface::Unitialized']
        );
    };

$container[ParamProcessor\ParamProcessorFactoryInterface::class] = function (Container $c) {
    return new ParamProcessor\ParamProcessorFactory([
        $c[ParamProcessor\Chain\SimpleParamProcessorFactory::class],
        $c[ParamProcessor\Chain\ComplexParamProcessorFactory::class]
    ]);
};

$container[ParamProcessor\Chain\SimpleParamProcessorFactory::class] = function () {
    return new ParamProcessor\Chain\SimpleParamProcessorFactory();
};

$container[ParamProcessor\Chain\ComplexParamProcessorFactory::class] = function (Container $c) {
    return new ParamProcessor\Chain\ComplexParamProcessorFactory(
        $c['BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryInterface::Unitialized']
    );
};

$container[BetterSerializer\DataBind\Reader\Instantiator\Factory\InstantiatorFactoryInterface::class] =
    function (Container $c) {
        return new BetterSerializer\DataBind\Reader\Instantiator\Factory\InstantiatorFactory([
            $c[BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard\StandardInstantiatorFactory::class],
            $c[BetterSerializer\DataBind\Reader\Instantiator\Factory\Deserialize\DeserializeInstantiatorFactory::class]
        ]);
    };

$container[BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard\StandardInstantiatorFactory::class] =
    function (Container $c) {
        return new BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard\StandardInstantiatorFactory(
            $c[ParamProcessor\ParamProcessorFactoryInterface::class]
        );
    };

$container[BetterSerializer\DataBind\Reader\Instantiator\Factory\Deserialize\DeserializeInstantiatorFactory::class] =
    function () {
        return new \BetterSerializer\DataBind\Reader\Instantiator\Factory\Deserialize\DeserializeInstantiatorFactory();
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

$container[BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactory::class] =
    function () {
        return new BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactory();
    };

$container[BetterSerializer\DataBind\Writer\Processor\Factory\RecursiveProcessorFactory::class] =
    function (Container $c) {
        return new BetterSerializer\DataBind\Writer\Processor\Factory\RecursiveProcessorFactory(
            $c[BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactory::class]
        );
    };

$container[BetterSerializer\DataBind\Writer\Processor\Factory\CachedProcessorFactory::class] =
    function (Container $c) {
        return new BetterSerializer\DataBind\Writer\Processor\Factory\CachedProcessorFactory(
            $c[BetterSerializer\DataBind\Writer\Processor\Factory\RecursiveProcessorFactory::class],
            $c[Doctrine\Common\Cache\Cache::class]
        );
    };

$container['BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface::Unitialized'] =
    function (Container $c) {
        return $c[BetterSerializer\DataBind\Writer\Processor\Factory\CachedProcessorFactory::class];
    };

$container[BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface::class] =
    function (Container $c) {
        $factory = $c['BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface::Unitialized'];

        $factory->addMetaDataChainMember(
            $c[BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain\SimplePropertyMember::class]
        );
        $factory->addMetaDataChainMember(
            $c[BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain\ComplexPropertyMember::class]
        );

        $factory->addTypeChainMember(
            $c[BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain\CollectionMember::class]
        );
        $factory->addTypeChainMember(
            $c[BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain\ObjectMember::class]
        );
        $factory->addTypeChainMember(
            $c[BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain\SimpleMember::class]
        );
        $factory->addTypeChainMember(
            $c[BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain\ExtensionMember::class]
        );
        $factory->addTypeChainMember(
            $c[BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain\ExtensionCollectionMember::class]
        );

        return $factory;
    };

$container[BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain\ComplexPropertyMember::class] =
    function (Container $c) {
        return new BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain\ComplexPropertyMember(
            $c['BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface::Unitialized'],
            $c[BetterSerializer\DataBind\Writer\Extractor\Factory\AbstractFactoryInterface::class]
        );
    };

$container[BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain\SimplePropertyMember::class] =
    function (Container $c) {
        return new BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain\SimplePropertyMember(
            $c[BetterSerializer\DataBind\Writer\Converter\ConverterFactoryInterface::class],
            $c[BetterSerializer\DataBind\Writer\Extractor\Factory\AbstractFactoryInterface::class]
        );
    };

$container[BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain\CollectionMember::class] =
    function (Container $c) {
        return new BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain\CollectionMember(
            $c[BetterSerializer\DataBind\Writer\Converter\ConverterFactoryInterface::class],
            $c['BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface::Unitialized']
        );
    };

$container[BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain\ObjectMember::class] =
    function (Container $c) {
        return new BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain\ObjectMember(
            $c['BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface::Unitialized'],
            $c[BetterSerializer\DataBind\Writer\MetaData\ContextualReaderInterface::class]
        );
    };

$container[BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain\SimpleMember::class] =
    function (Container $c) {
        return new BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain\SimpleMember(
            $c[BetterSerializer\DataBind\Writer\Converter\ConverterFactoryInterface::class]
        );
    };

$container[BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain\ExtensionMember::class] =
    function () {
        return new BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain\ExtensionMember();
    };

$container[BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain\ExtensionCollectionMember::class] =
    function (Container $c) {
        return new BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain\ExtensionCollectionMember(
            $c['BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface::Unitialized']
        );
    };

$container[BetterSerializer\DataBind\Writer\MetaData\ContextualReaderInterface::class] = function (Container $c) {
    return $c[BetterSerializer\DataBind\Writer\MetaData\CachedContextualReader::class];
};

$container[BetterSerializer\DataBind\Writer\MetaData\CachedContextualReader::class] = function (Container $c) {
    return new BetterSerializer\DataBind\Writer\MetaData\CachedContextualReader(
        $c[BetterSerializer\DataBind\Writer\MetaData\ContextualReader::class],
        $c[Doctrine\Common\Cache\Cache::class]
    );
};

$container[BetterSerializer\DataBind\Writer\MetaData\ContextualReader::class] = function (Container $c) {
    return new BetterSerializer\DataBind\Writer\MetaData\ContextualReader(
        $c[BetterSerializer\DataBind\MetaData\Reader\ReaderInterface::class]
    );
};

$container[BetterSerializer\DataBind\Writer\Converter\ConverterFactoryInterface::class] = function () {
    return new BetterSerializer\DataBind\Writer\Converter\ConverterFactory();
};

$container[BetterSerializer\DataBind\Writer\Extractor\Factory\AbstractFactoryInterface::class] = function () {
    return new BetterSerializer\DataBind\Writer\Extractor\Factory\AbstractFactory();
};

$container[BetterSerializer\DataBind\MetaData\Reader\Reader::class] = function (Container $c) {
    return new BetterSerializer\DataBind\MetaData\Reader\Reader(
        $c[BetterSerializer\Reflection\Factory\ReflectionClassFactoryInterface::class],
        $c[BetterSerializer\DataBind\MetaData\Reader\ClassReader\ClassReaderInterface::class],
        $c[BetterSerializer\DataBind\MetaData\Reader\PropertyReader\PropertiesReaderInterface::class],
        $c[BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\ConstructorParamsReaderInterface::class]
    );
};

$container[BetterSerializer\DataBind\MetaData\Reader\ReaderInterface::class] = function (Container $c) {
    return new BetterSerializer\DataBind\MetaData\Reader\CachedReader(
        $c[BetterSerializer\DataBind\MetaData\Reader\Reader::class],
        $c[Doctrine\Common\Cache\Cache::class]
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

$container['BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface::Unitialized'] = function () {
    return new BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactory();
};

$container[BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface::class] = function (Container $c) {
    $typeFactory = $c['BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface::Unitialized'];
    $typeFactory->addChainMember($c[TypeFactoryChain\SimpleMember::class]);
    $typeFactory->addChainMember($c[TypeFactoryChain\DateTimeMember::class]);
    $typeFactory->addChainMember($c[TypeFactoryChain\ExtensionMember::class]);
    $typeFactory->addChainMember($c[TypeFactoryChain\ExtensionCollectionMember::class]);
    $typeFactory->addChainMember($c[TypeFactoryChain\ObjectMember::class]);
    $typeFactory->addChainMember($c[TypeFactoryChain\InterfaceMember::class]);
    $typeFactory->addChainMember($c[TypeFactoryChain\ArrayMember::class]);
    $typeFactory->addChainMember($c[TypeFactoryChain\DocBlockArrayMember::class]);
    $typeFactory->addChainMember($c[TypeFactoryChain\MixedMember::class]);

    return $typeFactory;
};

$container[TypeFactoryChain\ArrayMember::class] = function (Container $c) {
    return new TypeFactoryChain\ArrayMember(
        $c['BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface::Unitialized']
    );
};

$container[TypeFactoryChain\ExtensionMember::class] = function (Container $c) {
    return new TypeFactoryChain\ExtensionMember(
        $c['BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface::Unitialized'],
        $c[BetterSerializer\DataBind\MetaData\Type\Parameters\ParserInterface::class]
    );
};

$container[TypeFactoryChain\ExtensionCollectionMember::class] =
    function (Container $c) {
        return new TypeFactoryChain\ExtensionCollectionMember(
            $c['BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface::Unitialized'],
            $c[BetterSerializer\DataBind\MetaData\Type\Parameters\ParserInterface::class]
        );
    };

$container[TypeFactoryChain\DateTimeMember::class] = function () {
    return new TypeFactoryChain\DateTimeMember();
};

$container[TypeFactoryChain\DocBlockArrayMember::class] = function (Container $c) {
    return new TypeFactoryChain\DocBlockArrayMember(
        $c['BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface::Unitialized']
    );
};

$container[TypeFactoryChain\MixedMember::class] = function (Container $c) {
    return new TypeFactoryChain\MixedMember(
        $c['BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface::Unitialized']
    );
};

$container[TypeFactoryChain\ObjectMember::class] = function () {
    return new TypeFactoryChain\ObjectMember();
};

$container[TypeFactoryChain\InterfaceMember::class] = function () {
    return new TypeFactoryChain\InterfaceMember();
};

$container[TypeFactoryChain\SimpleMember::class] = function () {
    return new TypeFactoryChain\SimpleMember();
};

$container[BetterSerializer\DataBind\Reader\Converter\ConverterFactoryInterface::class] = function () {
    return new BetterSerializer\DataBind\Reader\Converter\ConverterFactory();
};

$container[BetterSerializer\DataBind\Writer\Converter\ConverterFactoryInterface::class] = function () {
    return new BetterSerializer\DataBind\Writer\Converter\ConverterFactory();
};

$container[BetterSerializer\Reflection\Factory\ReflectionClassFactoryInterface::class] = function (Container $c) {
    return new BetterSerializer\Reflection\Factory\ReflectionClassFactory(
        $c[BetterSerializer\Reflection\UseStatement\UseStatementsExtractorInterface::class]
    );
};

$container[BetterSerializer\Reflection\UseStatement\UseStatementsExtractorInterface::class] = function (Container $c) {
    return new \BetterSerializer\Reflection\UseStatement\UseStatementsExtractor(
        $c[BetterSerializer\Reflection\UseStatement\Factory\CodeReaderFactoryInterface::class],
        $c[BetterSerializer\Reflection\UseStatement\ParserInterface::class]
    );
};

$container[BetterSerializer\Reflection\UseStatement\Factory\CodeReaderFactoryInterface::class] = function () {
    return new BetterSerializer\Reflection\UseStatement\Factory\CodeReaderFactory();
};

$container[BetterSerializer\Reflection\UseStatement\ParserInterface::class] = function () {
    return new BetterSerializer\Reflection\UseStatement\Parser();
};

$container[Doctrine\Common\Cache\Cache::class] = function (Container $c) {
    return $c[BetterSerializer\Cache\Factory::class]->getCache();
};

$container[BetterSerializer\Cache\Factory::class] = function () {
    return new BetterSerializer\Cache\Factory();
};

$container[BetterSerializer\DataBind\MetaData\Type\Parameters\ParserInterface::class] = function () {
    return new \BetterSerializer\DataBind\MetaData\Type\Parameters\Parser();
};

$container['TypeExtensionRegistrator'] = function (Container $c) {
    return new BetterSerializer\Extension\Registry\Registrator\ExtensionRegistrator(
        BetterSerializer\Common\TypeExtensionInterface::class,
        $c[TypeFactoryChain\ExtensionMember::class],
        $c[BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain\ExtensionMember::class],
        $c[BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain\ExtensionMember::class]
    );
};

$container['CollectionExtensionRegistrator'] = function (Container $c) {
    return new BetterSerializer\Extension\Registry\Registrator\ExtensionRegistrator(
        BetterSerializer\Common\CollectionExtensionInterface::class,
        $c[TypeFactoryChain\ExtensionCollectionMember::class],
        $c[BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain\ExtensionCollectionMember::class],
        $c[BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain\ExtensionCollectionMember::class]
    );
};

$container[BetterSerializer\Extension\Registry\ExtensionRegistryInterface::class] = function (Container $c) {
    return new BetterSerializer\Extension\Registry\ExtensionRegistry(
        [
            $c['TypeExtensionRegistrator'],
            $c['CollectionExtensionRegistrator']
        ]
    );
};

$container['InternalExtensions'] = function () {
    return [
        BetterSerializer\Extension\DoctrineCollection::class,
    ];
};

return $container;
