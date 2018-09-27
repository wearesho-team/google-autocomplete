# Changelog

## 2.0.0 [Unreleased]
### Added
- Required session token for requesting to Google-api-service
- Short/Full search mode for locations
- Special internal logic for LocationCollection for excluding duplicated locations
- method `getResults(): LocationCollection` for `Service`

### Changed
- Return typehint of `load(...): LocationCollection` => `load(...): ServiceInterface`
- The SearchQuery class is divided into two classes extends SearchQueryInterface (because of many optional parameters):
    - CitySearch
    - StreetSearch
- Queries structure:
    - removed all classes for queries into `Queries` directory

## 1.1.0 - Country configuration
### Release date - 2018-09-26

### Added
- Method `getCountry(): string` for `ConfigInterface`
- Optional parameter for `Config` constructor: `string $country = null`
- Optional environment variable: `GOOGLE_SERVICE_AUTOCOMPLETE_COUNTRY'`
- Documentation for contribute

### Changed
- Usage documentation for country configuration