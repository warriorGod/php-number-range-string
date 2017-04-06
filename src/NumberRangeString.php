<?php

namespace Tramsak\NumberRangeString;

/**
 * Support basic calculations on ranges (e.g. 1-2, 2-2, ...)
 */
class NumberRangeString
{

    protected $from;
    protected $to;
    // this is the one that got parsed from the range
    protected $outputSeparator;

    protected $defaultSeparator = '-';

    /**
     *
     * @param string|int $range Can
     */
    public function __construct($range)
    {
        $this->setRange($range);
    }

    public static function fromRange($range)
    {
        return new self($range);
    }

    public function add($days)
    {
        $this->from += $days;
        $this->to += $days;

        return $this;
    }

    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    public function countExclusive()
    {
        return abs($this->from - $this->to) - 1;
    }

    public function countInclusive()
    {
        // count from and start
        return abs($this->from - $this->to) + 1;
    }

    public function countNumeric()
    {
        return abs($this->from - $this->to);
    }

    public function moveTo($from)
    {
        $to = $from + $this->countNumeric();
        $this->setRange([$from, $to]);

        return $this;
    }

    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }

    public function expand(NumberRangeString $range)
    {
        if ($range->getFrom() < $this->getFrom()) {
            $this->from = $range->getFrom();
        }
        if ($range->getTo() > $this->getTo()) {
            $this->to = $range->getTo();
        }
        return $this;
    }

    public function moveBy($nbr)
    {
        $this->setRange([
            $this->from + $nbr
            , $this->to + $nbr
        ]);

        return $this;
    }

    /**
     * @param $range
     * @return self
     * @throws Exception
     */
    public function setRange($range)
    {
        $from = null;
        $to = null;
        $sep = $this->outputSeparator; // keep set separator
        if (is_array($range)) {
            $from = intval($range[0], 10);
            $to = intval($range[1], 10);
            $sep = isset($range[2]) ? $range[2] : $this->outputSeparator;
        } elseif (is_int($range)) {
            // proper integer
            $from = $range;
            $to = $range;
        } elseif (is_numeric($range)) {
            // integer pretending to be a string
            $from = intval($range, 10);
            $to = intval($range, 10);
        } else {
            // a proper string
            if (!preg_match('/((?:-|)\d+)([^\d]+?)((?:-|)\d+)/i', $range, $m)) {
                // can't find a range here ...
                throw new Exception(sprintf('Cant parse %s', $range));
            }

            $from = intval($m[1], 10);
            $sep = $m[2];
            $to = intval($m[3], 10);
        }

        $this->from = $from;
        $this->to = $to;
        $this->outputSeparator = $sep;

        return $this;
    }

    public function toString($separator = null)
    {
        if ($this->from == $this->to) {
            return (string)$this->from;
        } else {
            $sep = ($separator !== null ? $separator : $this->outputSeparator);
            if ($sep === null) {
                // no separator supplied, no separator found in the range
                $sep = $this->defaultSeparator;
            }
            return $this->from . $sep . $this->to;
        }
    }

    public function __toString()
    {
        return $this->toString();
    }
}