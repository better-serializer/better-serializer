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
class IdenticalTranslatorTest extends TestCase
{

    /**
     * @throws
     */
    public function testTranslate(): void
    {
        $propertyName = 'test';
        $propertyMetaData = $this->createMock(PropertyMetaDataInterface::class);
        $propertyMetaData->expects(self::once())
            ->method('getName')
            ->willReturn($propertyName);

        $translator = new IdenticalTranslator();
        $translated = $translator->translate($propertyMetaData);

        self::assertEquals($propertyName, $translated);
    }
}
