<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData;

use LogicException;
use RuntimeException;

/**
 * Class ReflectionPropertyMetadata
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData
 */
final class ReflectionPropertyMetadata extends AbstractReflectionPropertyMetaData
{

    /**
     * @return string
     */
    public function getOutputKey(): string
    {
        try {
            return parent::getOutputKey();
        } catch (RuntimeException | LogicException $e) {
        }

        return $this->getReflectionProperty()->getName();
    }
}
