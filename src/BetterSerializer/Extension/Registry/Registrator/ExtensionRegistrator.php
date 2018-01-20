<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Extension\Registry\Registrator;

use BetterSerializer\DataBind\MetaData\Type\Factory\Chain\ExtensibleChainMemberInterface as ExtensibleTypeFactory;
use BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain as ReaderTypeChain;
use BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain as WriterTypeChain;
use ReflectionClass;
use RuntimeException;

/**
 *
 */
final class ExtensionRegistrator implements ExtensionRegistratorInterface
{

    /**
     * @var string
     */
    private $extTypeInterface;

    /**
     * @var ExtensibleTypeFactory
     */
    private $typeFactory;

    /**
     * @var ReaderTypeChain\ExtensibleChainMemberInterface
     */
    private $extRdProcFactory;

    /**
     * @var WriterTypeChain\ExtensibleChainMemberInterface
     */
    private $extWrtProcFactory;

    /**
     * @param string $extTypeInterface
     * @param ExtensibleTypeFactory $typeFactory
     * @param ReaderTypeChain\ExtensibleChainMemberInterface $extRdProcFactory
     * @param WriterTypeChain\ExtensibleChainMemberInterface $extWrtProcFactory
     */
    public function __construct(
        string $extTypeInterface,
        ExtensibleTypeFactory $typeFactory,
        ReaderTypeChain\ExtensibleChainMemberInterface $extRdProcFactory,
        WriterTypeChain\ExtensibleChainMemberInterface $extWrtProcFactory
    ) {
        $this->extTypeInterface = $extTypeInterface;
        $this->typeFactory = $typeFactory;
        $this->extRdProcFactory = $extRdProcFactory;
        $this->extWrtProcFactory = $extWrtProcFactory;
    }

    /**
     * @return string
     */
    public function getExtTypeInterface(): string
    {
        return $this->extTypeInterface;
    }

    /**
     * @param ReflectionClass $reflClass
     * @return bool
     * @throws RuntimeException
     */
    public function register(ReflectionClass $reflClass): bool
    {
        if (!$this->isSupported($reflClass)) {
            return false;
        }

        $this->registerExtension($reflClass);

        return true;
    }

    /**
     * @param ReflectionClass $reflClass
     * @return bool
     */
    private function isSupported(ReflectionClass $reflClass): bool
    {
        return $reflClass->implementsInterface($this->extTypeInterface);
    }

    /**
     * @param ReflectionClass $reflClass
     * @throws RuntimeException
     */
    private function registerExtension(ReflectionClass $reflClass): void
    {
        $extensionClass = $reflClass->getName();

        $this->typeFactory->addExtensionClass($extensionClass);
        $this->extRdProcFactory->addExtensionClass($extensionClass);
        $this->extWrtProcFactory->addExtensionClass($extensionClass);
    }
}
