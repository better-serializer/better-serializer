<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\Writer;

use BetterSerializer\DataBind\MetaData\Reader\Reader;

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
