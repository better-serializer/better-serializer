<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Naming\PropertyNameTranslator;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class AnnotationTranslatorTest extends TestCase
{

    /**
     *
     */
    public function testTranslateWithoutAnnotation(): void
    {
        $name = 'testName';

        $propertyMetaData = $this->createMock(PropertyMetaDataInterface::class);
        $propertyMetaData->expects(self::once())
            ->method('getSerializationName')
            ->willReturn('');

        $delegateTranslator = $this->createMock(TranslatorInterface::class);
        $delegateTranslator->expects(self::once())
            ->method('translate')
            ->with($propertyMetaData)
            ->willReturn($name);

        $translator = new AnnotationTranslator($delegateTranslator);
        $translated = $translator->translate($propertyMetaData);

        self::assertSame($name, $translated);
    }

    /**
     *
     */
    public function testTranslateWithAnnotation(): void
    {
        $name = 'testName';

        $propertyMetaData = $this->createMock(PropertyMetaDataInterface::class);
        $propertyMetaData->expects(self::once())
            ->method('getSerializationName')
            ->willReturn($name);

        $delegateTranslator = $this->createMock(TranslatorInterface::class);
        $delegateTranslator->expects(self::exactly(0))
            ->method('translate');

        $translator = new AnnotationTranslator($delegateTranslator);
        $translated = $translator->translate($propertyMetaData);

        self::assertSame($name, $translated);
    }
}
