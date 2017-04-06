# php-number-range-string

Generate and modify number ranges in a string format.

# Features

- number ranges can be defined as a string, array or number (e.g. 3 as a single day range)
- ranges can be negative
- only tested on growing ranges (e.g from <= to)
- separator is "-" by default, but can be configured in array notation and when parsing.

# Usage

Please see tests for some examples. Here are a few.

```
NumberRangeString::fromRange('1-5')->moveBy(2) // 3-7
NumberRangeString::fromRange('1-20')->expand(new NumberRangeString('5-21')) // 1-21
NumberRangeString::fromRange('1-2')->countInclusive() // 2
NumberRangeString::fromRange('1-2')->countExclusive() // 0
NumberRangeString::fromRange('1-2')->countNumeric() // 1
NumberRangeString::fromRange('1-1')->countNumeric() // 0
NumberRangeString::fromRange('-5--1')->moveTo(1) // 1-5
new NumberRangeString([5,10, ' to ']) // 5 to 10
NumberRangeString::fromRange([5,10, ' to '])->add(1) // 6 to 11
```
