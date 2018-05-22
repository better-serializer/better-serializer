<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader;
use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeResolver\AnnotationPropertyTypeResolver;
use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeResolver\DocBlockPropertyTypeResolver;
use BetterSerializer\DataBind\MetaData\Type\Factory\Chain as TypeFactoryChain;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\HigherType;
use BetterSerializer\DataBind\Naming\PropertyNameTranslator\CamelCaseTranslator;
use BetterSerializer\DataBind\Naming\PropertyNameTranslator\IdenticalTranslator;
use BetterSerializer\DataBind\Naming\PropertyNameTranslator\SnakeCaseTranslator;
use BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard\ParamProcessor;
use BetterSerializer\DataBind\Reader\Processor\Factory as ReaderProcessorFactory;
use BetterSerializer\DataBind\Writer\Processor\Factory as WriterProcessorFactory;
use Pimple\Container;

$container = new Container();

$container[BetterSerializer\Serializer::class] = function (Container $c) {
    return new BetterSerializer\Serializer(
        $c[BetterSerializer\DataBind\Reader\ReaderInterface::class],
        $c[BetterSerializer\DataBind\Writer\WriterInterface::class]
    );
};

$container[BetterSerializer\DataBind\Naming\PropertyNameTranslator\TranslatorInterface::class] =
    function (Container $c) {
        return new BetterSerializer\DataBind\Naming\PropertyNameTranslator\AnnotationTranslator(
            new $c['translationNaming']
        );
    };

$container[BetterSerializer\DataBind\Naming\PropertyNameTranslator\IdenticalTranslator::class] =
    function () {
        return new BetterSerializer\DataBind\Naming\PropertyNameTranslator\IdenticalTranslator();
    };

$container[CamelCaseTranslator::class] =
    function () {
        return new CamelCaseTranslator();
    };

$container[SnakeCaseTranslator::class] =
    function () {
        return new SnakeCaseTranslator();
    };

$container[BetterSerializer\DataBind\Reader\ReaderInterface::class] = function (Container $c) {
    return new BetterSerializer\DataBind\Reader\Reader(
        $c[BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\StringTypeParserInterface::class],
        $c[BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface::class],
        $c[ReaderProcessorFactory\ProcessorFactoryInterface::class],
        $c[BetterSerializer\DataBind\Reader\Context\ContextFactoryInterface::class]
    );
};

$container[ReaderProcessorFactory\ProcessorFactory::class] =
    function () {
        return new ReaderProcessorFactory\ProcessorFactory();
    };

$container[ReaderProcessorFactory\RecursiveProcessorFactory::class] =
    function (Container $c) {
        return new ReaderProcessorFactory\RecursiveProcessorFactory(
            $c[ReaderProcessorFactory\ProcessorFactory::class]
        );
    };

$container[ReaderProcessorFactory\CachedProcessorFactory::class] =
    function (Container $c) {
        return new ReaderProcessorFactory\CachedProcessorFactory(
            $c[ReaderProcessorFactory\RecursiveProcessorFactory::class],
            $c[Doctrine\Common\Cache\Cache::class]
        );
    };

$container[ReaderProcessorFactory\ProcessorFactoryInterface::class] =
    function (Container $c) {
        return ReaderProcessorFactory\ProcessorFactoryBuilder::build(
            $c[ReaderProcessorFactory\CachedProcessorFactory::class],
            [
                $c[ReaderProcessorFactory\PropertyMetaDataChain\SimplePropertyMember::class],
                $c[ReaderProcessorFactory\PropertyMetaDataChain\ComplexPropertyMember::class]
            ],
            [
                $c[ReaderProcessorFactory\TypeChain\CollectionMember::class],
                $c[ReaderProcessorFactory\TypeChain\ClassMember::class],
                $c[ReaderProcessorFactory\TypeChain\SimpleMember::class],
                $c[ReaderProcessorFactory\TypeChain\ExtensionMember::class],
                $c[ReaderProcessorFactory\TypeChain\ExtensionCollectionMember::class]
            ]
        );
    };

$container[ReaderProcessorFactory\PropertyMetaDataChain\SimplePropertyMember::class] =
    function (Container $c) {
        return new ReaderProcessorFactory\PropertyMetaDataChain\SimplePropertyMember(
            $c[BetterSerializer\DataBind\Reader\Converter\ConverterFactoryInterface::class],
            $c[BetterSerializer\DataBind\Reader\Injector\Factory\AbstractFactoryInterface::class],
            $c[BetterSerializer\DataBind\Naming\PropertyNameTranslator\TranslatorInterface::class]
        );
    };

$container[ReaderProcessorFactory\PropertyMetaDataChain\ComplexPropertyMember::class] =
    function (Container $c) {
        return new ReaderProcessorFactory\PropertyMetaDataChain\ComplexPropertyMember(
            $c[ReaderProcessorFactory\CachedProcessorFactory::class],
            $c[BetterSerializer\DataBind\Reader\Injector\Factory\AbstractFactoryInterface::class],
            $c[BetterSerializer\DataBind\Naming\PropertyNameTranslator\TranslatorInterface::class]
        );
    };

$container[ReaderProcessorFactory\TypeChain\CollectionMember::class] =
    function (Container $c) {
        return new ReaderProcessorFactory\TypeChain\CollectionMember(
            $c[BetterSerializer\DataBind\Reader\Converter\ConverterFactoryInterface::class],
            $c[ReaderProcessorFactory\CachedProcessorFactory::class]
        );
    };

$container[ReaderProcessorFactory\TypeChain\ClassMember::class] =
    function (Container $c) {
        return new ReaderProcessorFactory\TypeChain\ClassMember(
            $c[ReaderProcessorFactory\CachedProcessorFactory::class],
            $c[BetterSerializer\DataBind\Reader\Instantiator\Factory\InstantiatorFactoryInterface::class],
            $c[BetterSerializer\DataBind\MetaData\Reader\ReaderInterface::class]
        );
    };

$container[ReaderProcessorFactory\TypeChain\SimpleMember::class] =
    function (Container $c) {
        return new ReaderProcessorFactory\TypeChain\SimpleMember(
            $c[BetterSerializer\DataBind\Reader\Converter\ConverterFactoryInterface::class]
        );
    };

$container[ReaderProcessorFactory\TypeChain\ExtensionMember::class] = function () {
    return new ReaderProcessorFactory\TypeChain\ExtensionMember();
};

$container[ReaderProcessorFactory\TypeChain\ExtensionCollectionMember::class] =
    function (Container $c) {
        return new ReaderProcessorFactory\TypeChain\ExtensionCollectionMember(
            $c[ReaderProcessorFactory\CachedProcessorFactory::class]
        );
    };

$container[ParamProcessor\ParamProcessorFactoryInterface::class] = function (Container $c) {
    return new ParamProcessor\ParamProcessorFactory([
        $c[ParamProcessor\Chain\SimpleParamProcessorFactory::class],
        $c[ParamProcessor\Chain\ComplexParamProcessorFactory::class]
    ]);
};

$container[ParamProcessor\Chain\SimpleParamProcessorFactory::class] = function (Container $c) {
    return new ParamProcessor\Chain\SimpleParamProcessorFactory(
        $c[BetterSerializer\DataBind\Naming\PropertyNameTranslator\TranslatorInterface::class]
    );
};

$container[ParamProcessor\Chain\ComplexParamProcessorFactory::class] = function (Container $c) {
    return new ParamProcessor\Chain\ComplexParamProcessorFactory(
        $c[ReaderProcessorFactory\CachedProcessorFactory::class],
        $c[BetterSerializer\DataBind\Naming\PropertyNameTranslator\TranslatorInterface::class]
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
        $c[WriterProcessorFactory\ProcessorFactoryInterface::class],
        $c[BetterSerializer\DataBind\Writer\Context\ContextFactoryInterface::class]
    );
};

$container[BetterSerializer\DataBind\Writer\Type\ExtractorInterface::class] = function () {
    return BetterSerializer\DataBind\Writer\Type\ExtractorBuilder::build();
};

$container[BetterSerializer\DataBind\Writer\Context\ContextFactoryInterface::class] = function () {
    return new BetterSerializer\DataBind\Writer\Context\ContextFactory();
};

$container[WriterProcessorFactory\ProcessorFactory::class] =
    function () {
        return new WriterProcessorFactory\ProcessorFactory();
    };

$container[WriterProcessorFactory\RecursiveProcessorFactory::class] =
    function (Container $c) {
        return new WriterProcessorFactory\RecursiveProcessorFactory(
            $c[WriterProcessorFactory\ProcessorFactory::class]
        );
    };

$container[WriterProcessorFactory\CachedProcessorFactory::class] =
    function (Container $c) {
        return new WriterProcessorFactory\CachedProcessorFactory(
            $c[WriterProcessorFactory\RecursiveProcessorFactory::class],
            $c[Doctrine\Common\Cache\Cache::class]
        );
    };

$container[WriterProcessorFactory\ProcessorFactoryInterface::class] =
    function (Container $c) {
        return WriterProcessorFactory\ProcessorFactoryBuilder::build(
            $c[WriterProcessorFactory\CachedProcessorFactory::class],
            [
                $c[WriterProcessorFactory\PropertyMetaDataChain\SimplePropertyMember::class],
                $c[WriterProcessorFactory\PropertyMetaDataChain\ComplexPropertyMember::class],
            ],
            [
                $c[WriterProcessorFactory\TypeChain\CollectionMember::class],
                $c[WriterProcessorFactory\TypeChain\ClassMember::class],
                $c[WriterProcessorFactory\TypeChain\SimpleMember::class],
                $c[WriterProcessorFactory\TypeChain\ExtensionMember::class],
                $c[WriterProcessorFactory\TypeChain\ExtensionCollectionMember::class],
            ]
        );
    };

$container[WriterProcessorFactory\PropertyMetaDataChain\ComplexPropertyMember::class] =
    function (Container $c) {
        return new WriterProcessorFactory\PropertyMetaDataChain\ComplexPropertyMember(
            $c[WriterProcessorFactory\CachedProcessorFactory::class],
            $c[BetterSerializer\DataBind\Writer\Extractor\Factory\AbstractFactoryInterface::class],
            $c[BetterSerializer\DataBind\Naming\PropertyNameTranslator\TranslatorInterface::class]
        );
    };

$container[WriterProcessorFactory\PropertyMetaDataChain\SimplePropertyMember::class] =
    function (Container $c) {
        return new WriterProcessorFactory\PropertyMetaDataChain\SimplePropertyMember(
            $c[BetterSerializer\DataBind\Writer\Converter\ConverterFactoryInterface::class],
            $c[BetterSerializer\DataBind\Writer\Extractor\Factory\AbstractFactoryInterface::class],
            $c[BetterSerializer\DataBind\Naming\PropertyNameTranslator\TranslatorInterface::class]
        );
    };

$container[WriterProcessorFactory\TypeChain\CollectionMember::class] =
    function (Container $c) {
        return new WriterProcessorFactory\TypeChain\CollectionMember(
            $c[BetterSerializer\DataBind\Writer\Converter\ConverterFactoryInterface::class],
            $c[WriterProcessorFactory\CachedProcessorFactory::class]
        );
    };

$container[WriterProcessorFactory\TypeChain\ClassMember::class] =
    function (Container $c) {
        return new WriterProcessorFactory\TypeChain\ClassMember(
            $c[WriterProcessorFactory\CachedProcessorFactory::class],
            $c[BetterSerializer\DataBind\Writer\MetaData\ContextualReaderInterface::class]
        );
    };

$container[WriterProcessorFactory\TypeChain\SimpleMember::class] =
    function (Container $c) {
        return new WriterProcessorFactory\TypeChain\SimpleMember(
            $c[BetterSerializer\DataBind\Writer\Converter\ConverterFactoryInterface::class]
        );
    };

$container[WriterProcessorFactory\TypeChain\ExtensionMember::class] =
    function () {
        return new WriterProcessorFactory\TypeChain\ExtensionMember();
    };

$container[WriterProcessorFactory\TypeChain\ExtensionCollectionMember::class] =
    function (Container $c) {
        return new WriterProcessorFactory\TypeChain\ExtensionCollectionMember(
            $c[WriterProcessorFactory\CachedProcessorFactory::class]
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
        return new BetterSerializer\DataBind\MetaData\Reader\PropertyReader\PropertiesReader(
            $c[Doctrine\Common\Annotations\AnnotationReader::class],
            $c[BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeResolver\TypeResolverChainInterface::class]
        );
    };

$container[AnnotationPropertyTypeResolver::class] = function (Container $c) {
    return new BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeResolver\AnnotationPropertyTypeResolver(
        $c[BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\StringTypeParserInterface::class]
    );
};

$container[BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeResolver\DocBlockPropertyTypeResolver::class] =
    function (Container $c) {
        return new BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeResolver\DocBlockPropertyTypeResolver(
            $c[phpDocumentor\Reflection\DocBlockFactoryInterface::class],
            $c[BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\StringTypeParserInterface::class]
        );
    };

$container[BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeResolver\TypeResolverChainInterface::class] =
    function (Container $c) {
        $typeReaders = [
            $c[AnnotationPropertyTypeResolver::class],
            $c[DocBlockPropertyTypeResolver::class],
        ];

        return new BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeResolver\TypeResolverChain(
            $c[BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface::class],
            $typeReaders
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
        $c[BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\StringTypeParserInterface::class],
        $c[BetterSerializer\DataBind\MetaData\Type\Factory\NativeTypeFactoryInterface::class]
    );
};

$container[BetterSerializer\DataBind\MetaData\Type\Factory\NativeTypeFactoryInterface::class] = function () {
    return new BetterSerializer\DataBind\MetaData\Type\Factory\NativeTypeFactory();
};

$container[ConstructorParamReader\TypeReader\Chained\DocBlockTypeReader::class] = function (Container $c) {
    return new ConstructorParamReader\TypeReader\Chained\DocBlockTypeReader(
        $c[BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface::class],
        $c[phpDocumentor\Reflection\DocBlockFactoryInterface::class],
        $c[BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\StringTypeParserInterface::class]
    );
};

$container[Doctrine\Common\Annotations\AnnotationReader::class] = function () {
    return BetterSerializer\DataBind\MetaData\Reader\AnnotationReaderFactory::newAnnotationReader();
};

$container[phpDocumentor\Reflection\DocBlockFactoryInterface::class] = function () {
    return phpDocumentor\Reflection\DocBlockFactory::createInstance();
};

$container[BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactory::class] = function () {
    return new BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactory();
};

$container[BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface::class] = function (Container $c) {
    return BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryBuilder::build(
        $c[BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactory::class],
        [
            $c[TypeFactoryChain\SimpleMember::class],
            $c[TypeFactoryChain\DateTimeMember::class],
            $c[TypeFactoryChain\ExtensionMember::class],
            $c[TypeFactoryChain\ExtensionCollectionMember::class],
            $c[TypeFactoryChain\ClassMember::class],
            $c[TypeFactoryChain\InterfaceMember::class],
            $c[TypeFactoryChain\ArrayMember::class],
        ]
    );
};

$container[TypeFactoryChain\ArrayMember::class] = function (Container $c) {
    return new TypeFactoryChain\ArrayMember(
        $c[BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactory::class]
    );
};

$container[TypeFactoryChain\ExtensionMember::class] = function (Container $c) {
    return new TypeFactoryChain\ExtensionMember(
        $c[BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactory::class],
        $c[BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\StringTypeParserInterface::class]
    );
};

$container[TypeFactoryChain\ExtensionCollectionMember::class] =
    function (Container $c) {
        return new TypeFactoryChain\ExtensionCollectionMember(
            $c[BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactory::class]
        );
    };

$container[TypeFactoryChain\DateTimeMember::class] = function () {
    return new TypeFactoryChain\DateTimeMember();
};

$container[TypeFactoryChain\ClassMember::class] = function () {
    return new TypeFactoryChain\ClassMember();
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

$container[BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\ParametersParserInterface::class] =
    function () {
        return new BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\ParametersParser();
    };

$container[BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\StringTypeParserInterface::class] =
    function (Container $c) {
        return new BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\StringTypeParser(
            $c[BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Detector\DetectorInterface::class],
            $c[BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\ParametersParserInterface::class]
        );
    };

$container[BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Detector\DetectorInterface::class] =
    function (Container $c) {
        return new BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Detector\Detector(
            $c[BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format\ParserChainInterface::class],
            $c[BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\ResolverChainInterface::class]
        );
    };

$container[BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format\ParserChainInterface::class] =
    function (Container $c) {
        return new BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format\ParserChain(
            [
                $c[BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format\DocBlockArrayParser::class],
                $c[BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format\SerializerFormatParser::class],
            ]
        );
    };

$container[BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format\DocBlockArrayParser::class] =
    function () {
        return new BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format\DocBlockArrayParser();
    };

$container[BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format\SerializerFormatParser::class] =
    function () {
        return new BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format\SerializerFormatParser();
    };

$container[BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\ResolverChainInterface::class] =
    function (Container $c) {
        return new BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\ResolverChain(
            [
                $c[BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\PrimitiveTypeResolver::class],
                $c[BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\HigherTypeResolver::class],
            ]
        );
    };

$container[BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\PrimitiveTypeResolver::class] =
    function () {
        return new BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\PrimitiveTypeResolver();
    };

$container[BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\HigherTypeResolver::class] =
    function (Container $c) {
        return new BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\HigherTypeResolver(
            [
                $c[HigherType\PassThroughGuesser::class],
                $c[HigherType\UseStmtGuesser::class],
            ],
            $c[HigherType\TypeClassResolverChainInterface::class]
        );
    };

$container[HigherType\PassThroughGuesser::class] = function () {
    return new HigherType\PassThroughGuesser();
};

$container[HigherType\UseStmtGuesser::class] = function (Container $c) {
    return new HigherType\UseStmtGuesser(
        $c[HigherType\NamespaceFragmentsParserInterface::class]
    );
};

$container[HigherType\NamespaceFragmentsParserInterface::class] = function () {
    return new HigherType\NamespaceFragmentsParser();
};

$container[HigherType\TypeClassResolverChainInterface::class] = function (Container $c) {
    return new HigherType\TypeClassResolverChain(
        [
            $c[HigherType\ClassResolver::class],
            $c[HigherType\InterfaceResolver::class],
            $c[HigherType\ExtensionResolver::class],
        ]
    );
};

$container[HigherType\ClassResolver::class] = function () {
    return new HigherType\ClassResolver();
};

$container[HigherType\InterfaceResolver::class] = function () {
    return new HigherType\InterfaceResolver();
};

$container[HigherType\ExtensionResolver::class] = function (Container $c) {
    return new HigherType\ExtensionResolver(
        $c[BetterSerializer\Extension\Registry\CollectionInterface::class]
    );
};

$container['TypeExtensionRegistrator'] = function (Container $c) {
    return new BetterSerializer\Extension\Registry\Registrator\Registrator(
        BetterSerializer\Common\TypeExtensionInterface::class,
        $c[TypeFactoryChain\ExtensionMember::class],
        $c[ReaderProcessorFactory\TypeChain\ExtensionMember::class],
        $c[WriterProcessorFactory\TypeChain\ExtensionMember::class]
    );
};

$container['CollectionExtensionRegistrator'] = function (Container $c) {
    return new BetterSerializer\Extension\Registry\Registrator\Registrator(
        BetterSerializer\Common\CollectionExtensionInterface::class,
        $c[TypeFactoryChain\ExtensionCollectionMember::class],
        $c[ReaderProcessorFactory\TypeChain\ExtensionCollectionMember::class],
        $c[WriterProcessorFactory\TypeChain\ExtensionCollectionMember::class]
    );
};

$container[BetterSerializer\Extension\Registry\RegistryInterface::class] = function (Container $c) {
    return new BetterSerializer\Extension\Registry\Registry(
        $c[BetterSerializer\Extension\Registry\CollectionInterface::class],
        [
            $c['TypeExtensionRegistrator'],
            $c['CollectionExtensionRegistrator']
        ]
    );
};

$container[BetterSerializer\Extension\Registry\CollectionInterface::class] = function () {
    return new BetterSerializer\Extension\Registry\Collection();
};

$container['InternalExtensions'] = function () {
    return [
        BetterSerializer\Extension\DoctrineCollection::class,
    ];
};

$container['translationNaming'] = IdenticalTranslator::class;

return $container;
