<?php

namespace Tramsak\NumberRangeString\Tests;

use Tramsak\NumberRangeString\NumberRangeString;

class NumberRangeStringTest extends \PHPUnit_Framework_TestCase
{

    public function _countInclusive()
    {
        return array(
            array(2, [-5, -4]),
            array(1, [-4, -4]),
            array(4, '-5--2'),
        );
    }

    /**
     * @dataProvider _countInclusive
     */
    public function testNumberRangeCountInclusive($equals, $range)
    {

        $this->assertEquals($equals, NumberRangeString::fromRange($range)->countInclusive());
    }

    public function _countExclusive()
    {
        return array(
            array(-1, [-4, -4]),
            array(0, [-5, -4]),
        );
    }

    /**
     * @dataProvider _countExclusive
     */
    public function testNumberRangeCountExclusive($equals, $range)
    {

        $this->assertEquals($equals, NumberRangeString::fromRange($range)->countExclusive());

    }

    public function _moveTo()
    {
        return array(
            array(-4, -4, [-4, -4], -4),
            array(0, 1, [-5, -4], 0),
        );
    }

    /**
     * @dataProvider _moveTo
     */
    public function testNumberRangeMoveTo($equalsFrom, $equalsTo, $range, $move)
    {
        $rangeObj = NumberRangeString::fromRange($range)->moveTo($move);
        $this->assertEquals($equalsFrom, $rangeObj->getFrom());
        $this->assertEquals($equalsTo, $rangeObj->getTo());
    }

    public function _moveBy()
    {
        return array(
            array(-3, -3, [-4, -4], 1),
            array(-5, -5, [-5, -5], 0),
        );
    }

    /**
     * @dataProvider _moveBy
     */
    public function testNumberRangeMoveBy($equalsFrom, $equalsTo, $range, $move)
    {
        $rangeObj = NumberRangeString::fromRange($range)->moveBy($move);
        $this->assertEquals($equalsFrom, $rangeObj->getFrom());
        $this->assertEquals($equalsTo, $rangeObj->getTo());
    }

    public function _toString()
    {
        return array(
            array('1', 1, null),
            array('2', 2, ''),
            array('1-2', [1,2], null),
        );
    }

    /**
     * @dataProvider _toString
     */
    public function testNumberRangeToString($equals, $range, $sep)
    {
        $this->assertEquals($equals, NumberRangeString::fromRange($range)->toString($sep));
    }

    public function testSimpleNegativeRangeParsing()
    {
        $this->assertEquals('-5--1', NumberRangeString::fromRange('-5--1'));
    }

    public function testExamples()
    {
        $this->assertEquals('3-7',  NumberRangeString::fromRange('1-5')->moveBy(2));
        $this->assertEquals('1-21', NumberRangeString::fromRange('1-20')->expand(new NumberRangeString('5-21')));
        $this->assertEquals('2',    NumberRangeString::fromRange('1-2')->countInclusive());
        $this->assertEquals('0',    NumberRangeString::fromRange('1-2')->countExclusive());
        $this->assertEquals('1',    NumberRangeString::fromRange('1-2')->countNumeric());
        $this->assertEquals('0',    NumberRangeString::fromRange('1-1')->countNumeric());
        $this->assertEquals('1-5',  NumberRangeString::fromRange('-5--1')->moveTo(1));
        $this->assertEquals('5 to 10',  new NumberRangeString([5,10, ' to ']));
        $this->assertEquals('6 to 11',  NumberRangeString::fromRange([5,10, ' to '])->add(1));
    }



}