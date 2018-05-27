<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain;

use BetterSerializer\DataBind\Naming\PropertyNameTranslator\TranslatorInterface;
use BetterSerializer\DataBind\Reader\Injector\Factory\AbstractFactoryInterface as InjectorFactoryInterface;

/**
 *
 */
abstract class InjectingChainMember extends ChainMember
{

    /**
     * @var InjectorFactoryInterface
     */
    protected $injectorFactory;

    /**
     * @var TranslatorInterface
     */
    protected $nameTranslator;

    /**
     * @param InjectorFactoryInterface $injectorFactory
     * @param TranslatorInterface $nameTranslator
     */
    public function __construct(InjectorFactoryInterface $injectorFactory, TranslatorInterface $nameTranslator)
    {
        $this->injectorFactory = $injectorFactory;
        $this->nameTranslator = $nameTranslator;
    }
}
