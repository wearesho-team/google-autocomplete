<?php

namespace Wearesho\GoogleAutocomplete;

use GuzzleHttp;

use Wearesho\GoogleAutocomplete\Enums;

/**
 * Class Service
 * @package Wearesho\GoogleAutocomplete
 */
class Service implements ServiceInterface
{
    protected const SESSION_TOKEN = 'session_token';
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

    public function __construct(ConfigInterface $config, GuzzleHttp\ClientInterface $client)
    {
        $this->config = $config;
        $this->client = $client;
    }

    /**
     * @param SearchQueryInterface $query
     *
     * @return LocationCollection
     * @throws Exceptions\InvalidResponse
     * @throws Exceptions\QueryException
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function load(SearchQueryInterface $query): LocationCollection
    {
        $queryType = $query->getType();
        
        $response = $this->client->request('GET', $this->config->getUrl(), [
            GuzzleHttp\RequestOptions::QUERY => [
                static::INPUT => $query->getInput(),
                static::TYPE => $queryType->equals(Enums\AddressPart::CITY())
                    ? static::CITIES
                    : static::ADDRESS,
                static::COMPONENTS => static::COUNTRY . ConfigInterface::UKRAINE,
                static::LANGUAGE => $query->getLanguage()->getValue(),
                static::SESSION_TOKEN => $query->getSessionToken(),
                static::KEY => $this->config->getKey(),
            ],
        ]);

        $responseBody = $response->getBody();
        $data = json_decode($responseBody, true);
        if (json_last_error() !== JSON_ERROR_NONE || !array_key_exists(static::STATUS, $data)) {
            throw new Exceptions\InvalidResponse($response);
        }

        $status = new Enums\SearchStatus($data[static::STATUS]);

        $this->validateSuccessStatus($status, $query);

        if ($this->isStatusZeroResults($status) || !$this->checkPredictionCollectionExist($data)) {
            return new LocationCollection([]);
        }

        $predictions = $data[static::PREDICTION_COLLECTION];

        if ($queryType->equals(Enums\AddressPart::CITY())) {
            return $this->fetchCities($predictions);
        } else {
            return $this->fetchStreets($predictions, $query->getCity());
        }
    }

    /**
     * @param Enums\SearchStatus   $status
     * @param SearchQueryInterface $query
     *
     * @throws Exceptions\QueryException
     */
    protected function validateSuccessStatus(Enums\SearchStatus $status, SearchQueryInterface $query): void
    {
        if (!$status->equals(Enums\SearchStatus::OK()) && !$this->isStatusZeroResults($status)) {
            throw new Exceptions\QueryException($query, $status);
        }
    }

    protected function checkMainTextExist(array $prediction): bool
    {
        return !(!array_key_exists(static::STRUCTURED_FORMATTING, $prediction)
            || !array_key_exists(static::MAIN_TEXT, $prediction[static::STRUCTURED_FORMATTING]));
    }

    protected function isStatusZeroResults(Enums\SearchStatus $status): bool
    {
        return $status->equals(Enums\SearchStatus::ZERO_RESULTS());
    }

    protected function checkDescriptionExist(array $prediction): bool
    {
        return array_key_exists(static::DESCRIPTION, $prediction);
    }

    protected function checkTermsExist(array $prediction): bool
    {
        return array_key_exists(static::TERM_COLLECTION, $prediction);
    }
    
    protected function checkTermValueExist(array $term): bool
    {
        return array_key_exists(static::TERM_VALUE, $term);
    }

    protected function checkPredictionCollectionExist(array $data): bool
    {
        return array_key_exists(static::PREDICTION_COLLECTION, $data);
    }

    private function fetchCities(array $predictions): LocationCollection
    {
        $locations = new LocationCollection();

        /** @var array $prediction */
        foreach ($predictions as $prediction) {
            if ($this->checkMainTextExist($prediction)) {
                $locations->append(new Location($prediction[static::STRUCTURED_FORMATTING][static::MAIN_TEXT]));
            }
        }

        return $locations;
    }

    private function fetchStreets(array $predictions, string $city = null): LocationCollection
    {
        $locations = new LocationCollection();

        if (!is_null($city) && !empty($city)) {
            foreach ($predictions as $prediction) {
                if (!$this->checkDescriptionExist($prediction) && !$this->checkTermsExist($prediction)) {
                    continue;
                }
                
                foreach ($prediction[static::TERM_COLLECTION] as $term) {
                    if (!$this->checkTermValueExist($term)) {
                        continue;
                    }

                    similar_text($city, $term[static::TERM_VALUE], $percentage);

                    if ($percentage >= static::MIN_SIMILARITY_PERCENTAGE) {
                        $locations->append(new Location($prediction[static::DESCRIPTION]));

                        continue;
                    }
                }
            }

            if (!$locations->count()) {
                return $this->fetchStreets($predictions);
            }
        } else {
            /** @var array $prediction */
            foreach ($predictions as $prediction) {
                if ($this->checkDescriptionExist($prediction)) {
                    $locations->append(new Location($prediction[static::DESCRIPTION]));
                }
            }
        }

        return $locations;
    }
}
