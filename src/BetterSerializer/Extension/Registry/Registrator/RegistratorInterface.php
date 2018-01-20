<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Extension\Registry\Registrator;

use ReflectionClass;

/**
 *
 */
interface RegistratorInterface
{

    /**
     * @return string
     */
    public function getExtTypeInterface(): string;

    /**
     * @param ReflectionClass $reflClass
     * @return bool
     */
    public function register(ReflectionClass $reflClass): bool;
}
