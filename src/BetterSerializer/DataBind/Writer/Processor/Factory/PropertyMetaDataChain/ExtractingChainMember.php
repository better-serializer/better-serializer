<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain;

use BetterSerializer\DataBind\Naming\PropertyNameTranslator\TranslatorInterface;
use BetterSerializer\DataBind\Writer\Extractor\Factory\AbstractFactoryInterface as ExtractorFactoryInterface;

/**
 *
 */
abstract class ExtractingChainMember extends ChainMember
{

    /**
     * @var ExtractorFactoryInterface
     */
    protected $extractorFactory;

    /**
     * @var TranslatorInterface
     */
    protected $nameTranslator;

    /**
     * @param ExtractorFactoryInterface $extractorFactory
     * @param TranslatorInterface $nameTranslator
     */
    public function __construct(ExtractorFactoryInterface $extractorFactory, TranslatorInterface $nameTranslator)
    {
        $this->extractorFactory = $extractorFactory;
        $this->nameTranslator = $nameTranslator;
    }
}
