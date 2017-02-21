<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\Type\TypeFactoryInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use phpDocumentor\Reflection\DocBlockFactoryInterface;

/**
 * Class ReaderFactory
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData
 */
final class ReaderFactory
{

    /**
     * @var DocBlockFactoryInterface
     */
    private $docBlockFactory;

    /**
     * @var TypeFactoryInterface
     */
    private $typeFactory;

    /**
     * @var Reader
     */
    private $reader;

    /**
     * ReaderFactory constructor.
     * @param DocBlockFactoryInterface $docBlockFactory
     * @param TypeFactoryInterface $typeFactory
     */
    public function __construct(DocBlockFactoryInterface $docBlockFactory, TypeFactoryInterface $typeFactory)
    {
        //DocBlockFactory::createInstance()
        $this->docBlockFactory = $docBlockFactory;
        $this->typeFactory = $typeFactory;
    }

    /**
     * @return Reader
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function createReader(): Reader
    {
        if ($this->reader !== null) {
            return $this->reader;
        }

        $annotationReader = $this->createAnnotationReader();
        $this->reader = new Reader(
            new ClassReader($annotationReader),
            new PropertyReader($annotationReader, $this->docBlockFactory, $this->typeFactory)
        );

        return $this->reader;
    }

    /**
     * @return AnnotationReader
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function createAnnotationReader(): AnnotationReader
    {
        AnnotationRegistry::registerAutoloadNamespace(
            "BetterSerializer\\DataBind\\MetaData\\Annotations",
            dirname(__DIR__, 4)
        );

        return new AnnotationReader();
    }
}
