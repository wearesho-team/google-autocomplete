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
    )
    {
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

        $explodedTerms = array_unique($explodedTerms, SORT_REGULAR);
        $explodedTermsCount = count($explodedTerms);
        $result = [];

        $a = $this->super_unique($explodedTerms);

        die;

        for ($i = 0; $i < $explodedTermsCount; $i++) {
            for ($j = $i + 1; $j < $explodedTermsCount; $j++) {
                if ($j == $explodedTermsCount) {
                    break;
                }

                var_dump(count(array_diff($explodedTerms[$i], $explodedTerms[$j])) === count($explodedTerms[$i]));

                if (count(array_intersect($explodedTerms[$i], $explodedTerms[$j]))) {
                    $result[] = $explodedTerms[$i];
                }
            }
        }

        var_dump($result);

        var_dump(call_user_func_array(
            'array_intersect',
            array_unique(
                array_map(function (Location $location): array {
                    return explode(' ', $location->getValue());
                }, (array)$this),
                SORT_REGULAR
            )
        ));

        $this->exchangeArray(array_map(
            function (array $terms): Location {
                return new Location(implode(' ', $terms));
            },
            call_user_func_array(
                'array_intersect',
                array_unique(
                    array_map(function (Location $location): array {
                        return [explode(' ', $location->getValue())];
                    }, (array)$this),
                    SORT_REGULAR
                )
            )
        ));
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
