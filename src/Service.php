<?php

namespace Wearesho\GoogleAutocomplete;

use GuzzleHttp;

use Wearesho\GoogleAutocomplete\Enums;
use Wearesho\GoogleAutocomplete\Exceptions;
use Wearesho\GoogleAutocomplete\Queries;

/**
 * Class Service
 * @package Wearesho\GoogleAutocomplete
 */
class Service implements ServiceInterface
{
    protected const SESSION_TOKEN = 'sessiontoken';
    protected const CITIES = '(cities)';
    protected const ADDRESS = 'address';
    protected const STATUS = 'status';
    protected const DESCRIPTION = 'description';
    protected const PREDICTION_COLLECTION = 'predictions';
    protected const PREDICTION_SINGLE = 'prediction';
    protected const TERM_COLLECTION = 'terms';
    protected const TERM_VALUE = 'value';
    protected const STRUCTURED_FORMATTING = 'structured_formatting';
    protected const MAIN_TEXT = 'main_text';
    protected const INPUT = 'input';
    protected const TYPE = 'types';
    protected const LANGUAGE = 'language';
    protected const COMPONENTS = 'components';
    protected const COUNTRY = 'country:';
    protected const KEY = 'key';
    protected const MIN_SIMILARITY_PERCENTAGE = 75;

    /** @var ConfigInterface */
    protected $config;

    /** @var GuzzleHttp\ClientInterface */
    protected $client;

    /** @var Queries\Interfaces\SearchQueryInterface */
    protected $query;

    /** @var LocationCollection */
    protected $locations;

    public function __construct(ConfigInterface $config, GuzzleHttp\ClientInterface $client)
    {
        $this->config = $config;
        $this->client = $client;
        $this->locations = new LocationCollection();
    }

    public function getResults(): LocationCollection
    {
        return $this->locations;
    }

    /**
     * @param Queries\Interfaces\SearchQueryInterface $query
     *
     * @return ServiceInterface
     * @throws Exceptions\InvalidResponse
     * @throws Exceptions\QueryException
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function load(Queries\Interfaces\SearchQueryInterface $query): ServiceInterface
    {
        $this->query = $query;

        $parameters = [
            static::INPUT => trim($this->query->getInput()),
            static::SESSION_TOKEN => $this->query->getToken(),
            static::LANGUAGE => $this->query->getLanguage()->getValue(),
            static::COMPONENTS => static::COUNTRY . mb_strtolower($this->config->getCountry()),
            static::KEY => $this->config->getKey(),
        ];

        if ($this->query instanceof Queries\Interfaces\CitySearchInterface) {
            $parameters[static::TYPE] = static::CITIES;
        } elseif ($this->query instanceof Queries\Interfaces\StreetSearchInterface) {
            $parameters[static::INPUT] = trim($this->query->getType()) . ' ' . $parameters[static::INPUT];
            $parameters[static::TYPE] = static::ADDRESS;
        } else {
            throw new Exceptions\InvalidQueryType($this->query);
        }

        $response = $this->client->request('GET', $this->config->getUrl(), [
            GuzzleHttp\RequestOptions::QUERY => $parameters,
        ]);

        $responseBody = $response->getBody();
        $data = json_decode($responseBody, true);

        if (json_last_error() !== JSON_ERROR_NONE || !array_key_exists(static::STATUS, $data)) {
            throw new Exceptions\InvalidResponse($response);
        }

        $status = new Enums\SearchStatus($data[static::STATUS]);

        $this->validateStatus($status);

        if ($this->existPredictionWrapper($data)) {
            $this->locations = $this->fetchResults($data[static::PREDICTION_COLLECTION]);
        }

        return $this;
    }

    /**
     * @param Enums\SearchStatus $status
     *
     * @throws Exceptions\QueryException
     */
    protected function validateStatus(Enums\SearchStatus $status): void
    {
        if (!$status->equals(Enums\SearchStatus::OK()) && !$status->equals(Enums\SearchStatus::ZERO_RESULTS())) {
            throw new Exceptions\QueryException($status, $this->query);
        }
    }

    protected function fetchResults(array $predictions): LocationCollection
    {
        $locations = new LocationCollection();

        if ($this->query instanceof Queries\Interfaces\CitySearchInterface) {
            foreach ($predictions as $prediction) {
                if ($this->existMainText($prediction)) {
                    $locations->append($this->instanceLocation($prediction));
                }
            }
        } elseif ($this->query instanceof Queries\Interfaces\StreetSearchInterface) {
            $city = $this->query->getCity();

            if (!is_null($city) && !empty($city)) {
                foreach ($predictions as $prediction) {
                    if (!$this->existDescription($prediction) && !$this->existTermWrapper($prediction)) {
                        continue;
                    }

                    foreach ($prediction[static::TERM_COLLECTION] as $term) {
                        if (!$this->existTermValue($term)) {
                            continue;
                        }

                        similar_text($city, $term[static::TERM_VALUE], $percentage);

                        if ($percentage >= static::MIN_SIMILARITY_PERCENTAGE) {
                            $locations->append($this->instanceLocation($prediction));

                            continue;
                        }
                    }
                }
            }

            if (!$locations->count()) {
                foreach ($predictions as $prediction) {
                    if ($this->existDescription($prediction)) {
                        $locations->append($this->instanceLocation($prediction));
                    }
                }
            }
        }

        return $locations->excludeDuplicates();
    }

    protected function instanceLocation(array $prediction): Location
    {
        return new Location(
            $this->query->getMode()->equals(Enums\SearchMode::SHORT())
                ? $prediction[static::STRUCTURED_FORMATTING][static::MAIN_TEXT]
                : $prediction[static::DESCRIPTION]
        );
    }

    protected function existMainText(array $prediction): bool
    {
        return !(!array_key_exists(static::STRUCTURED_FORMATTING, $prediction)
            || !array_key_exists(static::MAIN_TEXT, $prediction[static::STRUCTURED_FORMATTING]));
    }

    protected function existDescription(array $prediction): bool
    {
        return array_key_exists(static::DESCRIPTION, $prediction);
    }

    protected function existTermWrapper(array $prediction): bool
    {
        return array_key_exists(static::TERM_COLLECTION, $prediction);
    }

    protected function existTermValue(array $term): bool
    {
        return array_key_exists(static::TERM_VALUE, $term);
    }

    protected function existPredictionWrapper(array $data): bool
    {
        return array_key_exists(static::PREDICTION_COLLECTION, $data);
    }
}
