<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer;

use PHPUnit\Framework\TestCase;

/**
 * Class SerializationContextTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer
 */
class SerializationContextTest extends TestCase
{

    /**
     *
     */
    public function testEverything(): void
    {
        $groupsArray = ['g1', 'g2'];
        $context = new SerializationContext($groupsArray);

        self::assertSame($groupsArray, $context->getGroups());
    }
}
