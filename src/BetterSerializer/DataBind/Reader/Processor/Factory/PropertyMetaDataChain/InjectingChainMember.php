<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain;

use BetterSerializer\DataBind\Reader\Injector\Factory\AbstractFactoryInterface as InjectorFactoryInterface;

/**
 * Class ExtractingChainMember
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain
 */
abstract class InjectingChainMember extends ChainMember
{

    /**
     * @var InjectorFactoryInterface
     */
    protected $injectorFactory;

    /**
     * ExtractingChainMember constructor.
     * @param InjectorFactoryInterface $extractorFactory
     */
    public function __construct(InjectorFactoryInterface $extractorFactory)
    {
        $this->injectorFactory = $extractorFactory;
    }
}
