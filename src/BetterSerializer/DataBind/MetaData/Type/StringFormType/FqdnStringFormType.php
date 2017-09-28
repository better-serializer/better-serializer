<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType;

/**
 * Class FqdnStringFormType
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\StringFormType
 */
final class FqdnStringFormType implements StringFormTypeInterface
{

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $fqdn;

    /**
     * @var bool
     */
    private $isClass;

    /**
     * FqdnStringFormType constructor.
     * @param string $fqdn
     */
    public function __construct($fqdn)
    {
        $this->fqdn = $fqdn;
        $this->init();
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getStringType(): string
    {
        return $this->fqdn;
    }

    /**
     * @return bool
     */
    public function isClass(): bool
    {
        return $this->isClass;
    }

    /**
     *
     */
    private function init(): void
    {
        $this->isClass = class_exists($this->fqdn);

        if (!$this->isClass) {
            $this->namespace = '';

            return;
        }

        $parts = explode('\\', $this->fqdn);
        array_pop($parts);
        $this->namespace = implode('\\', $parts);
    }
}
