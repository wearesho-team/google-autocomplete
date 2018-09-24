<?php

namespace Wearesho\GoogleAutocomplete;

/**
 * Class LocationCollection
 * @package Wearesho\GoogleAutocomplete
 */
class LocationCollection extends \ArrayObject implements \JsonSerializable
{
    public function __construct(
        array $elements = [],
        int $flags = 0,
        string $iteratorClass = \ArrayIterator::class
    ) {
        foreach ($elements as $element) {
            $this->validateType($element);
        }

        parent::__construct($elements, $flags, $iteratorClass);
    }

    public function values(): LocationCollection
    {
        return new static(array_values((array)$this));
    }

    public function append($value)
    {
        $this->validateType($value);

        parent::append($value);
    }

    public function offsetSet($index, $value)
    {
        $this->validateType($value);

        parent::offsetSet($index, $value);
    }

    public function jsonSerialize(): array
    {
        return array_map(function (Location $location) {
            return $location->getValue();
        }, (array)$this);
    }

    protected function validateType($object): void
    {
        $objectType = get_class($object);

        if (!$object instanceof Location) {
            throw new \InvalidArgumentException("Element {$objectType} must be instance of " . Location::class);
        }
    }
}
