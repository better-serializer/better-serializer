<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain;

use BetterSerializer\DataBind\Writer\Extractor\Factory\AbstractFactoryInterface as ExtractorFactoryInterface;

/**
 * Class ExtractingChainMember
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain
 */
abstract class ExtractingChainMember extends ChainMember
{

    /**
     * @var ExtractorFactoryInterface
     */
    protected $extractorFactory;

    /**
     * ExtractingChainMember constructor.
     * @param ExtractorFactoryInterface $extractorFactory
     */
    public function __construct(ExtractorFactoryInterface $extractorFactory)
    {
        $this->extractorFactory = $extractorFactory;
    }
}
