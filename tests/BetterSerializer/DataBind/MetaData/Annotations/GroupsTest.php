<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Annotations;

use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Class GroupsTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Annotations
 */
class GroupsTest extends TestCase
{

    /**
     *
     */
    public function testEverything(): void
    {
        $groupsArray = ['value' => ['g1', 'g2']];
        $groups = new Groups($groupsArray);

        self::assertSame($groupsArray['value'], $groups->getGroups());
        self::assertSame(Groups::ANNOTATION_NAME, $groups->getAnnotationName());
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Value key missing for groups.
     */
    public function testThrowExceptionOnMisingValueField(): void
    {
        $groupsArray = ['g1', 'g2'];
        new Groups($groupsArray);
    }
}
