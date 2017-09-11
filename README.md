# BetterSerializer (PHP)

[![Build Status](https://travis-ci.org/better-serializer/better-serializer.svg?branch=master)](https://travis-ci.org/better-serializer/better-serializer)
[![Coverage Status](https://coveralls.io/repos/github/better-serializer/better-serializer/badge.svg?branch=master)](https://coveralls.io/github/better-serializer/better-serializer?branch=master)
[![Latest Stable Version](https://poser.pugx.org/better-serializer/better-serializer/version)](https://packagist.org/packages/better-serializer/better-serializer)
[![Total Downloads](https://poser.pugx.org/better-serializer/better-serializer/downloads)](https://packagist.org/packages/better-serializer/better-serializer)
[![Latest Unstable Version](https://poser.pugx.org/better-serializer/better-serializer/v/unstable)](//packagist.org/packages/better-serializer/better-serializer)
[![License](https://poser.pugx.org/better-serializer/better-serializer/license)](https://packagist.org/packages/better-serializer/better-serializer)
[![composer.lock available](https://poser.pugx.org/better-serializer/better-serializer/composerlock)](https://packagist.org/packages/better-serializer/better-serializer)

This library provides a general serializer for PHP. Currently only JSON serialization format is supported.
The project aims to be an alternative to [JmsSerializer](https://github.com/schmittjoh/serializer). It tries
to be faster then JmsSerializer and it also tries to sustain a better maintainable and understandable code base.
Also, as this is also a learning experiment, one of the goals is to have unit tests with 100% code coverage.

Except the above mentioned goals, the project also aims to provide some cool features - it tries to combine 
the best features from JmsSerializer and from [Jackson](https://github.com/FasterXML/jackson) in Java.

## Current state

Currently, only JSON de/serialization is implemented. It's possible to de/serialize complex nested data structures
(objects and arrays). Only arrays are supported as collection types for now.

## Performance

For now the code is only a proof of concept, but it is stabilizing more and more each day. 
It already yields interesting results. Without implementing
metadata caching, the serialization process is already 
[4-6x faster](tests/Performance/Serialization/JsonTest.php) than using JmsSerializer. 
The deserialization process is also faster, but only [cca 3.5x faster](tests/Performance/Deserialization/JsonTest.php).

There is also another benchmark, which compares JMS Serializer, [Ivory Serializer]() and Better Serialier. 
It also integrates Symfony serializer, but I needed to comment it out, because it was 100-300x slower 
than Better Serializer.
You can find a fork of it [here](https://github.com/better-serializer/ivory-serializer-benchmark) and try it 
on your own. For best results, please disable XDebug while running the tests.

Here are some of the results:

```bash
$ php bin/benchmark  --iteration 1000 --horizontal-complexity 2 --vertical-complexity 2
Ivory: Done!
JMS: Done!
BetterSerializer: Done!

+------------------+----------------+--------+
| Serializer       | Duration (sec) | Factor |
+------------------+----------------+--------+
| BetterSerializer | 0.001188s      | 1.00x  |
| Ivory            | 0.002222s      | 1.87x  |
| JMS              | 0.002901s      | 2.44x  |
+------------------+----------------+--------+

$ php bin/benchmark  --iteration 100 --horizontal-complexity 10 --vertical-complexity 10
Ivory: Done!
JMS: Done!
BetterSerializer: Done!

+------------------+----------------+--------+
| Serializer       | Duration (sec) | Factor |
+------------------+----------------+--------+
| BetterSerializer | 0.011377s      | 1.00x  |
| Ivory            | 0.037377s      | 3.29x  |
| JMS              | 0.046837s      | 4.12x  |
+------------------+----------------+--------+

$ php bin/benchmark  --iteration 1 --horizontal-complexity 100 --vertical-complexity 200
Ivory: Done!
JMS: Done!
BetterSerializer: Done!

+------------------+----------------+--------+
| Serializer       | Duration (sec) | Factor |
+------------------+----------------+--------+
| BetterSerializer | 1.040695s      | 1.00x  |
| Ivory            | 4.191479s      | 4.03x  |
| JMS              | 4.466160s      | 4.29x  |
+------------------+----------------+--------+

$ php bin/benchmark  --iteration 1 --horizontal-complexity 200 --vertical-complexity 200
Ivory: Done!
JMS: Done!
BetterSerializer: Done!

+------------------+----------------+--------+
| Serializer       | Duration (sec) | Factor |
+------------------+----------------+--------+
| BetterSerializer | 4.523851s      | 1.00x  |
| Ivory            | 15.580491s     | 3.44x  |
| JMS              | 18.571867s     | 4.11x  |
+------------------+----------------+--------+
```

**This means that you can now effectively de/serialize 4x more entities (e.g. in API results) using the same 
amount of time than before!**

Regarding the performance gains - I'd like someone to check the measured values, since the results seem quite great
and I'm suspicious myself :).

## Features

You can check out the features in the [features page](doc/Features.md). Please also check the supported 
[annotations documentation](doc/Annotations.md).

## Requirements

This library requires PHP 7.1 and it won't work with older versions. Older versions won't be supported.

## Usage

The usage is described [here](doc/Usage.md).

The de/serializaton annotations are described [here](doc/Annotations.md).

## Future Plans
- ~~metadata caching~~
- XML and YAML support
- various collection classes support (Doctrine collections, internal PHP collections like SplStack)
- data injection using class constructors (~~internal~~ and static), which should improve performance even more
- various features import from JmsSerializer and Jackson
- framework integrations

**Contributions are welcome! :)**
