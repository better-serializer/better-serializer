# BetterSerializer (PHP)

## Extensions

For custom de/serialising of selected data types, there are three possible options:

1) Custom type extensions (e.g. BooleanString)
2) Existing non-collection class/interface extensions
3) Existing collection class/interface extensions

All the extensions need to implement special interfaces to be able to register them
with the serializer.

**Beware!** The interface API isn't considered to be stable yet, it might change during
the development process of the serializer. Since the project is quite young, there
might be changes, especially in the lower release versions. On the other side,
if there will be changes, they aren't expected to be very big. 

### Custom type extensions

Custom type extensions are extensions designated for special processing of existing types
(either primitive types, or classes and interfaces). If there are multiple ways of de/serialising
one concrete type, custom types (with custom type names) are the way to achieve that. 

It can be used for example to transform 
boolean `true`/`false` values into string `'yes'`/`'no'` values. This is implemented
as `BooleanString` [custom type example](../tests/BetterSerializer/Helper/DataBind/BooleanStringExtension.php) below:

```php
use BetterSerializer\Common\TypeExtensionInterface;
use BetterSerializer\DataBind\MetaData\Type\Parameters\ParametersInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeEnum;
use BetterSerializer\DataBind\Reader\Context\ContextInterface as ReadContext;
use BetterSerializer\DataBind\Writer\Context\ContextInterface as WriteContext;

/**
 *
 */
final class BooleanStringExtension implements TypeExtensionInterface
{

    /**
     * @var ParametersInterface
     */
    private $parameters;

    /**
     * @const string
     */
    private const TYPE = 'BooleanString';

    /**
     * @const string
     */
    private const TYPE_TRUE = 'yes';

    /**
     * @const string
     */
    private const TYPE_FALSE = 'no';

    /**
     * @param ParametersInterface $parameters
     */
    public function __construct(ParametersInterface $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @param WriteContext $context
     * @param mixed $data
     */
    public function appendData(WriteContext $context, $data): void
    {
        $value = $data ? self::TYPE_TRUE : self::TYPE_FALSE;
        $context->writeSimple($value);
    }

    /**
     * @param ReadContext $context
     * @return mixed
     */
    public function extractData(ReadContext $context)
    {
        $value = $context->getCurrentValue();

        return $value === self::TYPE_TRUE;
    }

    /**
     * @return string
     */
    public static function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return null|string
     */
    public static function getReplacedType(): ?string
    {
        return TypeEnum::BOOLEAN;
    }
}
```

Every custom type extension has to implement the 
[BetterSerializer\Common\TypeExtensionInterface](../src/BetterSerializer/Common/TypeExtensionInterface.php) 
interface.
The most important method is the `getType()` method, which returns the name of the custom type.
When the extension is registered, the defined custom type can be used as type property 
of the @Property annotation like this: `@Serializer\Property(type="BooleanString")`. 
When registering a made-up type, it is quite important to define the original replacement type
using the `getReplacedType()` method. This definition will help in some internal optimization
processes, so it is recommended not to omit this definition.  

Another quite important part is the configuration parameters for the custom extension. 
It is possible to configure each serialized property separately, using configuration parameters.
You can use integer and string parameters this way: 
`@Serializer\Property(type="BooleanString(param1='string', param2=6)")` 
These parameters are then injected into the Extension object upon instantiation. This also
means, that there is a separate extension instance for each property annotated with the given
custom type, which consumes more memory, but is faster in the end.

The `appendData()` method is intended for data conversion, when serializing the replaced data type,
the `extractData()` method serves for data conversion in the opposite direction (deserialization). 

### Existing non-collection class/interface extensions

If there is a special de/serialization process for a given class or interface globally,
type extensions can be used for this use case too. It is then sufficient when the `getType()`
method returns the FQCN (fully qualified class name) of the given class or interface,
which effects in overriding of the default de/serialisation processing globally.
The return value of the `getReplacedType()` method can be `null` in such case.

Everything else can be used the same way as described in the previous chapter.

## Existing collection class/interface extensions

It is possible to write a custom extension for every possible collection type e.g. Doctrine 
collection, internal PHP collections (SplStack, SplQueue, etc...). Check the [Doctrine
collection](../src/BetterSerializer/Extension/DoctrineCollection.php) extension implementation below: 

```php
use BetterSerializer\Common\CollectionExtensionInterface;
use BetterSerializer\Common\CollectionAdapterInterface;
use BetterSerializer\DataBind\MetaData\Type\Parameters\ParametersInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Iterator;
use RuntimeException;
use Traversable;

/**
 *
 */
final class DoctrineCollection implements CollectionExtensionInterface
{

    /**
     * @var ParametersInterface
     */
    private $parameters;

    /**
     * @const string
     */
    private const TYPE = Collection::class;

    /**
     * @param ParametersInterface $parameters
     */
    public function __construct(ParametersInterface $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public static function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return null|string
     */
    public static function getReplacedType(): ?string
    {
        return null;
    }

    /**
     * @param mixed $collection
     * @return bool
     * @throws RuntimeException
     */
    public function isEmpty($collection): bool
    {
        if (!$collection instanceof Collection) {
            throw new RuntimeException(sprintf('%s is not a Doctrine collection.', get_class($collection)));
        }

        return $collection->isEmpty();
    }

    /**
     * @param Object $collection
     * @return CollectionAdapterInterface
     * @throws RuntimeException
     */
    public function getAdapter($collection): CollectionAdapterInterface
    {
        if (!$collection instanceof Collection) {
            throw new RuntimeException(sprintf('%s is not a Doctrine collection.', get_class($collection)));
        }

        return $this->createAdapter($collection);
    }

    /**
     * @return CollectionAdapterInterface
     */
    public function getNewAdapter(): CollectionAdapterInterface
    {
        $collection = new ArrayCollection();

        return $this->createAdapter($collection);
    }

    /**
     * @param Collection $collection
     * @return CollectionAdapterInterface
     */
    private function createAdapter(Collection $collection): CollectionAdapterInterface
    {
        return new class($collection) implements CollectionAdapterInterface {
            /**
             * @var Collection
             */
            private $collection;

            /**
             * @param Collection $collection
             */
            public function __construct(Collection $collection)
            {
                $this->collection = $collection;
            }

            /**
             * @return Iterator|Traversable
             */
            public function getIterator(): Iterator
            {
                return $this->collection->getIterator();
            }

            /**
             * @param int|string $offset
             * @return bool
             */
            public function offsetExists($offset): bool
            {
                return $this->collection->containsKey($offset);
            }

            /**
             * @param int|string $offset
             * @return mixed
             */
            public function offsetGet($offset)
            {
                return $this->collection->get($offset);
            }

            /**
             * @param int|string $offset
             * @param mixed $value
             */
            public function offsetSet($offset, $value): void
            {
                $this->collection->set($offset, $value);
            }

            /**
             * @param int|string $offset
             */
            public function offsetUnset($offset): void
            {
                $this->collection->remove($offset);
            }

            /**
             * @return Collection|mixed
             */
            public function getCollection()
            {
                return $this->collection;
            }
        };
    }
}
``` 

Collection extensions need to implement the 
[BetterSerializer\Common\CollectionExtensionInterface](../src/BetterSerializer/Common/CollectionExtensionInterface.php)
interface to be able to work properly. It is possible to define multiple processing extensions
for one collection type similarly as it is with primitive types and general classes, 
using custom made-up type definitions mentioned in the first chapter of this document.
For globally general processing of the given collection type, it will be sufficient when
the `getTye()` method will return the FQCN of the collection class or interface.

Since the collection types handling is special, quite a few more things need to be implemented
in the extension, comparing to the simpler general type extensions. 
The `isEmpty()` method
should return true or false, if the given input collection is empty or not respectively. 
The `getAdapter()` and `getNewAdapter()` methods are used to return a general adapter 
for the handled collection class. The adapter needs to implement the
[BetterSerializer\Common\CollectionAdapterInterface](../src/BetterSerializer/Common/CollectionAdapterInterface.php)
interface to be able to work with the serializer.
The `getNewAdapter()` method needs to create a new instance of the given collection class
as well, so the serializer core doesn't need to know anything about the collection class
instantiation. 
The adapter interface contains the method `getIterator()`, which needs to return a general 
php core iterator for the given collection class, for the serializer to be able to iterate over 
the collection elements. It also needs to define the methods of the php core's `ArrayAccess`
interface for the serializer to be able to get the elements from the collection
respectively set them into the collection. 
Last but not least, the adapter needs to implement the `getCollection()` method, to be able to return
the wrapped collection instance to the serializer.

To be able to use the collection extension with the serializer, the property of a class needs to be annotated this way:
`@Serializer\Property(type="Collection<Door>")`. This is the type definition of the doctrine collection extension
type, which expects to have the following use clause defined in the file:
`use Doctrine\Common\Collections\Collection;`. When the use clause is missing, it is needed to define the type
using the fully classified class name: `@Serializer\Property(type="Doctrine\Common\Collections\Collection<Door>")`

Defining the collection key types is not supported yet.

### Custom extensions registration

To be able to use the custom extension, it needs to be registered in the SerializerBuilder the following way:

```php
use use BetterSerializer\Serializer;

$builder = new Builder();
$builder->addExtension(BooleanStringExtension::class);
```

And that's it. After that, the extension will be used by the serializer. Currently there is no framework integration
for this library, so only manual extension registration is possible. Later, there will be framework integrations
with simpler extension registration using framework configuration systems.
