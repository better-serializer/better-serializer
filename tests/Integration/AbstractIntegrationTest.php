<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace Integration;

use BetterSerializer\Builder;
use BetterSerializer\Helper\DataBind\BooleanStringExtension;
use BetterSerializer\Serializer;
use JMS\Serializer\Serializer as JmsSerializer;
use JMS\Serializer\SerializerBuilder;
use PHPUnit\Framework\TestCase;

/**
 *
 */
abstract class AbstractIntegrationTest extends TestCase
{

    /**
     * @var Serializer[]
     */
    private static $serializers = [];

    /**
     * @var JmsSerializer
     */
    private static $jmsSerializer;

    /**
     * @const string
     */
    protected const EXTENSIONS = 'extensions';

    /**
     * @const string
     */
    protected const CACHE = 'cache';

    /**
     * @const string
     */
    protected const NAMING_STRATEGY = 'naming_strategy';

    /**
     * @param string[] $options
     * @param bool $dontCache
     * @return Serializer
     * @throws \InvalidArgumentException
     * @throws \Pimple\Exception\UnknownIdentifierException
     * @throws \RuntimeException
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    protected function getSerializer(array $options = [], bool $dontCache = false): Serializer
    {
        $options = array_merge(
            [
                self::EXTENSIONS => [
                    BooleanStringExtension::class,
                ],
            ],
            $options
        );

        return $this->buildSerializer($options, $dontCache);
    }

    /**
     * @param string[] $options
     * @param bool $dontCache
     * @return Serializer
     * @throws \InvalidArgumentException
     * @throws \Pimple\Exception\UnknownIdentifierException
     * @throws \RuntimeException
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    protected function getCachedSerializer(array $options = [], bool $dontCache = false): Serializer
    {
        $options = array_merge(
            [
                self::CACHE => true,
                self::EXTENSIONS => [
                    BooleanStringExtension::class,
                ],
            ],
            $options
        );

        return $this->buildSerializer($options, $dontCache);
    }

    /**
     * @param string[] $options
     * @param bool $dontCache
     * @return Serializer
     * @throws \InvalidArgumentException
     * @throws \Pimple\Exception\UnknownIdentifierException
     * @throws \RuntimeException
     * @SuppressWarnings(PHPMD.ElseExpression)
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    private function buildSerializer(array $options, bool $dontCache = false): Serializer
    {
        $cacheKey = var_export($options, true);

        if (isset(self::$serializers[$cacheKey])) {
            return self::$serializers[$cacheKey];
        }

        $builder = new Builder();

        if (isset($options[self::EXTENSIONS])) {
            foreach ($options[self::EXTENSIONS] as $extension) {
                $builder->addExtension($extension);
            }
        }

        if (isset($options[self::CACHE])) {
            if (extension_loaded('apcu') && ini_get('apc.enabled')) {
                $builder->enableApcuCache();
            } else {
                $builder->enableFilesystemCache(dirname(__DIR__, 2) . '/cache/better-serializer');
            }
        }

        if (isset($options[self::NAMING_STRATEGY])) {
            $builder->setNamingStrategy($options[self::NAMING_STRATEGY]);
        }

        $serializer = $builder->createSerializer();

        if ($dontCache) {
            return $serializer;
        }

        self::$serializers[$cacheKey] = $serializer;

        return $serializer;
    }

    /**
     * @return JmsSerializer
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \InvalidArgumentException
     * @throws \JMS\Serializer\Exception\InvalidArgumentException
     * @throws \JMS\Serializer\Exception\RuntimeException
     */
    protected function getJmsSerializer(): JmsSerializer
    {
        if (self::$jmsSerializer === null) {
            self::$jmsSerializer = SerializerBuilder::create()
                ->setCacheDir(dirname(__DIR__, 2) . '/cache/jms-serializer')
                ->build();
        }

        return self::$jmsSerializer;
    }
}
