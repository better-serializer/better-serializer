<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

/**
 *
 */
final class AnnotationReaderFactory
{

    /**
     * @var AnnotationReader
     */
    private static $annotationReader;

    /**
     * @return AnnotationReader
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function newAnnotationReader(): AnnotationReader
    {
        if (self::$annotationReader === null) {
            AnnotationRegistry::registerLoader('class_exists');
            self::$annotationReader = new AnnotationReader();
        }

        return self::$annotationReader;
    }
}
