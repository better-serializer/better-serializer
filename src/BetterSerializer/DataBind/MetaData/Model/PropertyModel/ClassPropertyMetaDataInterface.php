<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Model\PropertyModel;

/**
 *
 */
interface ClassPropertyMetaDataInterface extends PropertyMetaDataInterface
{

    /**
     * @return string
     */
    public function getObjectClass(): string;
}
