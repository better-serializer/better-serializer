<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Type;

use PHPUnit\Framework\TestCase;

/**
 *
 */
class ExtractorBuilderTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testBuild(): void
    {
        $extractor = ExtractorBuilder::build();

        self::assertInstanceOf(ExtractorInterface::class, $extractor);
    }
}
