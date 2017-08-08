# BetterSerializer (PHP)

## Usage

To use the serializer, install it using Composer:

```bash
$ composer require better-serializer/better-serializer
```

Since the project started to grow quite fast, it was impossible to work with it without a dependency injection container.
As the development DI container, [Pimple](https://pimple.symfony.com/) was chosen, because of its simplicity.

To be able to use BetterSerializer, for now you have to have Pimple installed as well:

```bash
$ composer require pimple/pimple:~3.0
``` 

After that the serializer can be instantiated the following way:

```php
use use BetterSerializer\Serializer;
use BetterSerializer\Common\SerializationType;

$container = require dirname(__DIR__) . '/../dev/di.pimple.php';
$serializer = $container->offsetGet(Serializer::class);

// serialization
$serializer->serialize($data, SerializationType::JSON());

// deserialization
$serializer->deserialize($jsonString, $type, SerializationType::JSON());
```

