<?php

namespace Wearesho\GoogleAutocomplete;

use GuzzleHttp;

/**
 * Class Service
 * @package Wearesho\GoogleAutocomplete
 */
class Service
{
    protected const CITIES = '(cities)';
    protected const ADDRESS = 'address';

    /** @var ConfigInterface */
    protected $config;

    /** @var GuzzleHttp\ClientInterface */
    protected $client;

    public function __construct(ConfigInterface $config, GuzzleHttp\ClientInterface $client)
    {
        $this->config = $config;
        $this->client = $client;
    }

    public function load(SearchDataInterface $searchData): array
    {
        $googleMapsType = [
            Type::STREET => static::ADDRESS,
            Type::CITY => static::CITIES,
        ][$searchData->getType()];

        $response = $this->client->request('GET', $this->config->getUrl(), [
            'query' => [
                'input' => $searchData->getInput(),
                'types' => $googleMapsType,
                'components' => 'country:' . ConfigInterface::UKRAINE_CODE,
                'language' => $searchData->getLanguage(),
                'key' => $this->config->getKey(),
            ],
        ]);

        $data = json_decode($response->getBody(), true);
        if (json_last_error() !== JSON_ERROR_NONE || !array_key_exists('status', $data)) {
            throw new Exceptions\InvalidResponse((string)$response->getBody());
        }

        $status = $data['status'];
        if ($status === Status::ZERO_RESULTS) {
            return [];
        }

        if ($status !== Status::OK) {
            throw new Exceptions\RequestException("Request failed with status {$status}: {$response->getBody()}");
        }

        if (!array_key_exists('predictions', $data)) {
            return [];
        }

        $result = [];

        foreach ($data['predictions'] as $prediction) {
            if ($googleMapsType === static::CITIES) {
                if (array_key_exists('description', $prediction)) {
                    $result[] = $prediction['description'];
                }
                continue;
            }

            if (!array_key_exists('structured_formatting', $prediction)
                || !array_key_exists('main_text', $prediction['structured_formatting'])
            ) {
                continue;
            }

            $result[] = $prediction['structured_formatting']['main_text'];
        }

        return $result;
    }
}
