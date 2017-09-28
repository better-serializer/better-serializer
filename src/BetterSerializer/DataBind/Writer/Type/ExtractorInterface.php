<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Type;

use BetterSerializer\DataBind\MetaData\Type\TypeInterface;

/**
 * Class StringTypeExtractor
 * @author mfris
 * @package BetterSerializer\DataBind\Writer
 */
interface ExtractorInterface
{
    /**
     * @param $data
     * @return TypeInterface
     */
    public function extract($data): TypeInterface;
}
