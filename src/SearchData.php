<?php

namespace Wearesho\GoogleAutocomplete;

/**
 * Class SearchData
 * @package Wearesho\GoogleAutocomplete
 */
class SearchData implements SearchDataInterface
{
    /** @var string */
    protected $input;

    /** @var string */
    protected $type;

    /** @var string */
    protected $language;

    public function __construct(string $input, string $type, string $language)
    {
        $this->input = $input;

        $this
            ->setType($type)
            ->setLanguage($language);
    }

    public function getInput(): string
    {
        return $this->input;
    }

    public function getType(): string
    {
        return $this->type;
    }

    protected function setType(string $type): SearchData
    {
        if (!in_array($type, [Type::CITY, Type::STREET,])) {
            throw new \InvalidArgumentException("Invalid address type: {$type}");
        }

        $this->type = $type;
        return $this;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    protected function setLanguage(string $language): SearchData
    {
        if (!in_array($language, [Language::RU, Language::UK,])) {
            throw new \InvalidArgumentException("Invalid language: {$language}");
        }

        $this->language = $language;
        return $this;
    }
}
