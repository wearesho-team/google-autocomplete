<?php

namespace Wearesho\GoogleAutocomplete;

use Wearesho\BaseCollection;

/**
 * Class LocationCollection
 * @package Wearesho\GoogleAutocomplete
 */
class LocationCollection extends BaseCollection
{
    public function type(): string
    {
        return Location::class;
    }

    /**
     * Removes duplicates of location names if they have same names.
     *
     * The algorithm compares only words, not the whole line, and if there is a difference of at least one character,
     * then the name is not considered duplicates
     *
     * @example ["street Main", "Main street"] => ["street Main"]
     * @example ["street Main", "Main st."] => ["street Main", "Main st."]
     *
     * @return LocationCollection without duplicated locations
     */
    public function excludeDuplicates(): LocationCollection
    {
        if ($this->count() < 2) {
            return $this;
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

        return $this;
    }
}
