# RandomPassword

A PHP library that uses cryptographically-secure randomization to generate a password of a given length from given sets of characters.

## Installation

With [Composer](http://getcomposer.org) installed on your computer and initialized for your project, run this command in your project’s root directory:

```bash
composer require lamansky/random-password
```

Requires PHP 7.1 or above.

## Examples

```php
<?php
use Lamansky\RandomPassword\RandomPasswordFactory;

// The alpha() static method returns a factory that will generate passwords
// which alternate between uppercase and lowercase characters.
$rpf = RandomPasswordFactory::alpha();
echo $rpf->generate(8);  // sJtNbZtA
echo $rpf->generate(10); // UcBkCwHdYm

// A numeric() factory uses only numbers.
echo RandomPasswordFactory::numeric()->generate(8); // 54088998

// An alphanumeric() factory rotates between lowercase letters, uppercase
// letters, and numbers.
echo RandomPasswordFactory::alphanumeric()->generate(10); // r9Tj4Nw5Qn

// If you want a truly random alphanumeric password, without rotation between
// character sets, you can instead construct your own factory with a `uln`
// configuration string. Because passwords generated from such a factory
// are more random, they will, ironically, sometimes look *less* random, since
// such passwords could theoretically spell a word, or consist solely of
// uppercase letters, for example.
echo (new RandomPasswordFactory('uln'))->generate(10); // fWQeV8WBNn

// A loweralphanumeric() factory is like alphanumeric() but without uppercase letters.
echo RandomPasswordFactory::loweralphanumeric()->generate(8); // p7h2q4e2

// An ascii() factory rotates between lowercase, uppercase, numbers, and symbols.
$rpf = RandomPasswordFactory::ascii();
$rpf->generate(12); // 7R_y5M#f8E@t
$rpf->generate(12); // ^fU2!zH9#bR3

// A custom() factory will use only the characters you specify.
echo RandomPasswordFactory::custom('abc')->generate(12); // cbbcaccaabab

```

## API

The library consists of a single class: `Lamansky\RandomPassword\RandomPasswordFactory`.

### Constructor Parameters

1. Optional: `$set_codes` (string or null): A configuration string indicating the character sets that should be used in generating the random password. If this is set to `null`, the default of `u l n` (equivalent to the configuration of the static `alphanumeric()` method) will be used.
2. Optional: `$custom_set` (string): A set of characters to be used if `$set_codes` includes `c`.

#### Set Codes

The `$set_codes` string is used to specify which characters should be used in generating random passwords. The available character sets are:

| Code | Set Name              | Set Characters |
| ---- | --------------------- | -------------- |
| `L`  | Lowercase             | `abcdefghijklmnopqrstuvwxyz` |
| `l`  | Unambiguous Lowercase | `abcdefhjkmnpqrstuvwxyz`<br>(Letters i, g, l, and o are omitted.) |
| `U`  | Uppercase             | `ABCDEFGHIJKLMNOPQRSTUVWXYZ` ||
| `u`  | Unambiguous Uppercase | `ABCDEFHJKLMNPQRSTUVWXYZ`<br>(Letters I, G, and O are omitted.) |
| `N`  | Numbers               | `1234567890` ||
| `n`  | Unambiguous Numbers   | `2345789`<br>(Numbers 1, 6, and 0 are omitted.) |
| `s`  | Symbols               | `!@#^*_` |
| `c`  | Custom                | Specified in the `$custom_set` variable. |

Passwords will be generated by rotating between different space-separated groups in your configuration string. Here are some sample values for `$set_codes` with corresponding behavior:

| Configuration String | Behavior | Sample Password |
| -------------------- | -------- | --------------- |
| `u l n`              | The password will be generated by rotating between the `u`, `l`, and `n` sets (uppercase, lowercase, and numbers). The order of the rotation will be randomized. | `jR5nX8eH` |
| `uln`                | Every character of the password will have a chance of being drawn from the `u`, `l`, or `n` sets. | `29QVYbC7` |
| `ul n`               | The password will be generated by rotating between letters (a mix of uppercase and lowercase) and numbers. | `9n5h4D8X` |

### Static Factory Methods

The `RandomPasswordFactory` class has various static factory methods, each of which serves as a shortcut for constructing a factory using a preconfigured value for `$set_codes`.

| Static Method         | Set Codes | Sample Password |
| --------------------- | --------- | --------------- |
| `alpha()`             | `u l`     | `sJtNbZtA`      |
| `numeric()`           | `N`       | `54088998`      |
| `alphanumeric()`      | `u l n`   | `r9Tj4Nw5`      |
| `loweralphanumeric()` | `l n`     | `p7h2q4e2`      |
| `ascii()`             | `u l n s` | `7R_y5M#f`      |
| `custom($custom_set)` | `c`       |                 |

### The `generate()` Method

#### Parameters

1. `$length` (int): The number of characters in the password to be generated. For security reasons, it is strongly recommended that this be set to `12` or higher.
2. Optional: `$set_codes` (string or null): If set, overrides the global `$set_codes` value set in the constructor.
3. Optional: `$custom_set` (string): If set, overrides the global `$custom_set` value set in the constructor.

#### Return Value

Returns a random password as a string.

## Unit Tests

To run the development test suite, execute this command:

```bash
./vendor/phpunit/phpunit/phpunit tests
```
