<?php
declare(strict_types=1);

/**
 * @author  mfris
 */
namespace BetterSerializer\DataBind\MetaData\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

/**
 * Class ReaderFactory
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData
 */
final class ReaderFactory
{


    /**
     * @var Reader
     */
    private $reader;

    /**
     * @return Reader
     */
    public function createReader(): Reader
    {
        if ($this->reader !== null) {
            return $this->reader;
        }

        $annotationReader = $this->createAnnotationReader();
        $this->reader = new Reader(
            new ClassReader($annotationReader),
            new PropertyReader($annotationReader)
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
