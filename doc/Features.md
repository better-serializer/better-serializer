# BetterSerializer (PHP)

## Features

Regarding the features from other serializers - none of them are implemented yet :) 
BUT, other features are already done:

### Docblock annotations compatibility

Using `@var` docblock annotations in class properties, you don't need to define the `@Type` serialization annotations, 
which would contain duplicit information in most of the cases. The only exception are typed collections, which 
are not implemented yet.

So in case you're wondering, this class definition works well with BetterSerializer:

```php
class Car
{

    /**
     * @var string
     */
    private $title;

    /**
     * @var Radio
     */
    private $radio;

    /**
     * @var Door[]
     */
    private $doors;
```

### No need to use full classified class names

You don't have to declare full classified class name in the `@var` or `@Type` annotations. 
The serializer automatically checks for the class existence in the same namespace 
which the owning class resides in.

That means, that in case you have e.g. the following classes in the `Dto` namespace:
- Car
- Door

You don't need to type fully quallyfied class name in the referencing `@var` tag, classes
in the same and nested namespace can reference each other withot the whole namespace prefix.

That means that you can write this:

```php
namespace Dto;

class Car
{

    /**
     * @var Door[]
     */
    private $doors;
}

class Door
{
}
```

instead of this:

```php
namespace Dto;

class Car
{

    /**
     * @var Dto\Door[]
     */
    private $doors;
}

class Door
{
}
```

### Deserialization using natural constructors

There are two possible object instantiation methods in this serializer. The first one is the one, 
which is being used by every other serializer - doctrine instantiator, which is actually a hack, but it works well.

This serializer also implements constructor parameters detection, which it then tries to pair with the corresponding
class properties. If the algorithm detects, that all the mandatory constructor parameters are bound to some
corresponding class properties, it will use standard instantiation method for object creation - it injects all the data
using the natural constructor of the class (by calling `ReflectionClass::newInstanceArgs()`).

This feature has some advantages, but it also may cause some drawbacks.

Advantages:
- faster instantiation in most cases
- cleaner instantiation (may be combined with some data validation or internal state recreation)

Drawbacks:
- if there is some complex data processing in the object instantiation process, or some slower validation,
  the instantiation process may be slower  

It's up to the user to decide, whether the slower data validation (and consequential data correctness)
brings higher added value, or not. 
