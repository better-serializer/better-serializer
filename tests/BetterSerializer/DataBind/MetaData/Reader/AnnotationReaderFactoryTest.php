<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;

/**
 * Class AnnotationReaderFactoryTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 */
class AnnotationReaderFactoryTest extends TestCase
{

    /**
     *
     */
    public function testNewAnnotationReader(): void
    {
        $annotReaderFactory = new AnnotationReaderFactory();
        $reader1 = $annotReaderFactory->newAnnotationReader();
        $reader2 = $annotReaderFactory->newAnnotationReader();

        self::assertInstanceOf(AnnotationReader::class, $reader1);
        self::assertSame($reader1, $reader2);
    }
}
