<?php
/**
 * @author  mfris
 */
declare(strict_types=1);

namespace BetterSerializer\DataBind;

use BetterSerializer\DataBind\MetaData\Reader;

/**
 * Class Writer
 * @author mfris
 * @package BetterSerializer\DataBind
 */
final class Writer
{

    /**
     * @var Reader
     */
    private $metaDataReader;

    /**
     * Writer constructor.
     * @param Reader $metaDataReader
     */
    public function __construct(Reader $metaDataReader)
    {
        $this->metaDataReader = $metaDataReader;
    }
}
