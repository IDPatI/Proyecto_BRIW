# Inflector

[![Release](https://img.shields.io/packagist/v/ICanBoogie/Inflector.svg)](https://packagist.org/packages/icanboogie/inflector)
[![Code Quality](https://img.shields.io/scrutinizer/g/ICanBoogie/Inflector/master.svg)](https://scrutinizer-ci.com/g/ICanBoogie/Inflector)
[![Code Coverage](https://img.shields.io/coveralls/ICanBoogie/Inflector/master.svg)](https://coveralls.io/r/ICanBoogie/Inflector)
[![Packagist](https://img.shields.io/packagist/dm/icanboogie/inflector.svg?maxAge=2592000)](https://packagist.org/packages/icanboogie/inflector)

A multilingual inflector that transforms words from singular to plural, underscore to camel case, and formats strings in
various ways. Inflections are localized, the default english inflections for pluralization, singularization, and
uncountable words are kept in [lib/Inflections/en.php](lib/Inflections/en.php).

Inflections are currently available for the following languages:

- English (`en`)
- French (`fr`)
- Norwegian Bokmal (`nb`)
- Portuguese (`pt`)
- Spanish (`es`)
- Turkish (`tr`)



#### Installation

```bash
composer require icanboogie/inflector
```



## Usage

These are some examples of the inflector with the `en` locale (default).

```php
<?php

use ICanBoogie\Inflector;

$inflector = Inflector::get(Inflector::DEFAULT_LOCALE);
# or
$inflector = Inflector::get('en');
# or
$inflector = Inflector::get();

# pluralize

$inflector->pluralize('post');                       // "posts"
$inflector->pluralize('child');                      // "children"
$inflector->pluralize('sheep');                      // "sheep"
$inflector->pluralize('words');                      // "words"
$inflector->pluralize('CamelChild');                 // "CamelChildren"

# singularize

$inflector->singularize('posts');                    // "post"
$inflector->singularize('children');                 // "child"
$inflector->singularize('sheep');                    // "sheep"
$inflector->singularize('word');                     // "word"
$inflector->singularize('CamelChildren');            // "CamelChild"

# camelize

$inflector->camelize('active_model', Inflector::UPCASE_FIRST_LETTER);
# or
$inflector->camelize('active_model');
// 'ActiveModel'

$inflector->camelize('active_model', Inflector::DOWNCASE_FIRST_LETTER);
// 'activeModel'

$inflector->camelize('active_model/errors');
// 'ActiveModel\Errors'

$inflector->camelize('active_model/errors', Inflector::DOWNCASE_FIRST_LETTER);
// 'activeModel\Errors'

# underscore

$inflector->underscore('ActiveModel');               // 'active_model'
$inflector->underscore('ActiveModel\Errors');        // 'active_model/errors'
$inflector->underscore('Less Active Phrase');        // 'less_active_phrase'
$inflector->underscore('Number 1 Test');             // 'number_1_test'
$inflector->underscore('Johnny5 Still Alive');       // 'johnny5_still_alive'
$inflector->underscore('Lots   of   Spaces');        // 'lots_of_spaces'

# humanize

$inflector->humanize('employee_salary');             // "Employee salary"
$inflector->humanize('author_id');                   // "Author"

# titleize

$inflector->titleize('man from the boondocks');      // "Man From The Boondocks"
$inflector->titleize('x-men: the last stand');       // "X Men: The Last Stand"
$inflector->titleize('TheManWithoutAPast');          // "The Man Without A Past"
$inflector->titleize('raiders_of_the_lost_ark');     // "Raiders Of The Lost Ark"

# ordinal

$inflector->ordinal(1);                              // "st"
$inflector->ordinal(2);                              // "nd"
$inflector->ordinal(1002);                           // "nd"
$inflector->ordinal(1003);                           // "rd"
$inflector->ordinal(-11);                            // "th"
$inflector->ordinal(-1021);                          // "st"

# ordinalize

$inflector->ordinalize(1);                           // "1st"
$inflector->ordinalize(2);                           // "2nd"
$inflector->ordinalize(1002);                        // "1002nd"
$inflector->ordinalize(1003);                        // "1003rd"
$inflector->ordinalize(-11);                         // "-11th"
$inflector->ordinalize(-1021);                       // "-1021st"

# uncountable

$inflector->is_uncountable("advice");                // true
$inflector->is_uncountable("weather");               // true
$inflector->is_uncountable("cat");                   // false
```

Helpers makes it easy to use default locale inflections.

```php
<?php

namespace ICanBoogie;

echo pluralize('child');                             // "children"
echo pluralize('genou', 'fr');                       // "genoux"
echo singularize('lærere', 'nb');                    // "lærer"
echo pluralize('üçgen', 'tr');                       // "üçgenler"
```


## About inflections

Inflections are localized, the configurators are kept in [lib/Inflections/en.php](lib/Inflections/en.php). Since v2.1,
these configurators are auto-loaded classes, which means, in theory, you could add your own or overwrite those already
defined by specifying another `ICanBoogie\\Inflections\\` in your `composer.json` file.



## Acknowledgements

Most of the code and documentation was adapted from [Ruby On Rails](http://rubyonrails.org/)'s
[Inflector](http://api.rubyonrails.org/classes/ActiveSupport/Inflector.html) and
[David Celis](https://github.com/davidcelis)' [inflections](https://github.com/davidcelis/inflections).

Significant differences:

- Better support of accentuated characters.
- The Ruby module separator `::` as been replaced by the PHP namespace separator `\`.
- The plural of "octopus" is "octopuses" (not "octopi"), the plural of "virus" is "viruses"
  (not viri) and the pural of "cow" is "cows" (not "kine").
- The following methods have been removed: `tableize`, `classify`, `demodulize`,
  `constantize`, `deconstantize` and `foreign_key`. They can be easily implemented in specific inflectors.
- Added the `hyphenate` method, which is a combination of `underscore` and `dasherize`.
- One specifies `true` rather than `false` to `camelize()` to downcase the first letter of the camel cased string.



## Getting started

**Inflector** expects to work in UTF-8, which is the default encoding character set starting
PHP 5.6, for older versions please use `mb_internal_encoding()` as follows:

```php
<?php

namespace ICanBoogie;

// …

mb_internal_encoding('UTF-8');

titleize("été_aux_âmes_inouïes"); // Été Aux Âmes Inouïes
```



----------



## Continuous Integration

The project is continuously tested by [GitHub actions](https://github.com/ICanBoogie/Inflector/actions).

[![Tests](https://github.com/ICanBoogie/Inflector/workflows/test/badge.svg?branch=master)](https://github.com/ICanBoogie/Inflector/actions?query=workflow%3Atest)
[![Static Analysis](https://github.com/ICanBoogie/Inflector/workflows/static-analysis/badge.svg?branch=master)](https://github.com/ICanBoogie/Inflector/actions?query=workflow%3Astatic-analysis)
[![Code Style](https://github.com/ICanBoogie/Inflector/workflows/code-style/badge.svg?branch=master)](https://github.com/ICanBoogie/Inflector/actions?query=workflow%3Acode-style)



## Code of Conduct

This project adheres to a [Contributor Code of Conduct](CODE_OF_CONDUCT.md). By participating in this project and its
community, you are expected to uphold this code.



## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.



## License

**icanboogie/inflector** is released under the [BSD-3-Clause](LICENSE).
