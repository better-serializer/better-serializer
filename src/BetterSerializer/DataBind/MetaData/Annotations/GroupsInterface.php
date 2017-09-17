<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Annotations;

/**
 * Class Property
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Annotations
 */
interface GroupsInterface extends AnnotationInterface
{
    /**
     * @return string[]
     */
    public function getGroups(): array;
}
