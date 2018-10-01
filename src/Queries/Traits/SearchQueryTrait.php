<?php

namespace Wearesho\GoogleAutocomplete\Queries\Traits;

use Wearesho\GoogleAutocomplete\Enums;

/**
 * Trait SearchQueryTrait
 * @package Wearesho\GoogleAutocomplete\Queries\Traits
 */
trait SearchQueryTrait
{
    /** @var string */
    protected $input;

    /** @var Enums\SearchLanguage */
    protected $language;

    /** @var Enums\SearchMode */
    protected $mode;

    /** @var string */
    protected $token;

    /**
     * @inheritdoc
     */
    public function getInput(): string
    {
        return $this->input;
    }

    /**
     * @inheritdoc
     */
    public function getMode(): Enums\SearchMode
    {
        return $this->mode;
    }

    /**
     * @inheritdoc
     */
    public function getLanguage(): Enums\SearchLanguage
    {
        return $this->language;
    }

    /**
     * @inheritdoc
     */
    public function getToken(): string
    {
        return $this->token;
    }
}
