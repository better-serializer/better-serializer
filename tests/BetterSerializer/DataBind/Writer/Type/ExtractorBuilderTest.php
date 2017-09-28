<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Type;

use PHPUnit\Framework\TestCase;

/**
 * Class ExtractorBuilderTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Type
 */
class ExtractorBuilderTest extends TestCase
{

    /**
     *
     */
    public function testBuild(): void
    {
        $builder = new ExtractorBuilder();
        $extractor = $builder->build();

        self::assertInstanceOf(ExtractorInterface::class, $extractor);
    }
}
