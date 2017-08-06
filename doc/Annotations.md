# BetterSerializer (PHP)

## Annotations

For now, there aren't many annotations supported, but the plan is to implement most of the features
from JMSSerializer and from Jackson. Fingers crossed :)

### @Property

Defines the property name and type.
Has to be defined on a class property.

Properties:
- **name**: defined name can override the actual property name during de/serialization
- **type**: type definition, most of the times it is unnecessary (because the serializer can read the `@var` 
docblock annotations), with the exception of typed collections (not implemented yet).


Example:

```php
use BetterSerializer\DataBind\MetaData\Annotations as Serializer;

class Car
{

    /**
     * @var string
     * @Serializer\Property(name="serializedTitle", type="string")
     */
    private $title;
}
```

In this case the object of the class Car will have the title property named as `serializedTitle`
in the serialized output or in the deserialized input.

The `type` property defines the property type. The type attribute can have parameters and parameters can be used 
by the serialization/deserialization process.

The following types are supported currently:


| Type                                                     | Description                                      |
|----------------------------------------------------------|--------------------------------------------------|
| bool                                                     | Primitive boolean                                |
| int                                                      | Primitive integer                                |
| float                                                    | Primitive double                                 |
| string                                                   | Primitive string                                 |
| array<T>                                                 | A list of type T (T can be any available type).  |
|                                                          | Examples:                                        |
|                                                          | array<string>, array<MyNamespace\MyObject>, etc. |
| T                                                        | Where T is a fully qualified class name.         | 

### @BoundToProperty

Describes a relation between a given class property and a constructor parameter.
Has to be defined on a constructor method for each constructor argument, whose name differs from the corresponding 
class parameter, which the constructor argument is bound to. 

```php
use BetterSerializer\DataBind\MetaData\Annotations as Serializer;

class Car
{

    /**
     * @var string
     */
    private $title;
    
    /**
     * @param string $specialTitle
     * @Serializer\BoundToProperty(propertyName="title", argumentName="specialTitle")
     */
    public function __construct(string $specialTitle)
    {
        $this->title = $specialTitle;
    }
}

In this case, the serializer knows, that the `specialTitle` constructor argument is being assigned to the `title`
class property.
