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

    public function excludeDuplicates(): void
    {
        if ($this->count() < 2) {
            return;
        }

        $explodedTerms = array_map(function (Location $location): array {
            return explode(' ', $location->getValue());
        }, (array)$this);

        $explodedTermsCount = count($explodedTerms);
        $result = [];

        for ($i = 0; $i < $explodedTermsCount - 1; $i++) {
            for ($j = $i + 1; $j < $explodedTermsCount; $j++) {
                if (array_count_values($explodedTerms[$i]) == array_count_values($explodedTerms[$j])) {
                    unset($explodedTerms[$i]);
                    break;
                }
            }
        }

        foreach ($explodedTerms as $term) {
            $result[] = new Location(implode(' ', $term));
        }

        $this->exchangeArray($result);
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
