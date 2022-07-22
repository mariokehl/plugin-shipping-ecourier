# Release Notes for eCourier (bamboo software)

## v1.0.3 (2022-07-22)

### Added
- When registering the order, the house number of the delivery address is now validated. If this is not available or entered incorrectly in the additional address field, an error is output and the shipment cannot be registered

## v1.0.2 (2022-07-13)

### Added
- When the order is registered, the length of the postal code stored in the delivery address is validated. If this is not 5 digits for Germany or 4 digits for Austria, an error is output and the shipment cannot be registered

### Fixed
- PHP 8 compatibility indicator set after source code check

## v1.0.1 (2022-05-06)

### Changed
- Minor cleanup (removed unused method)
- In the description, the cross-references to the plentymarkets manual have been adjusted

## v1.0.0 (2022-02-25)

### Added
- Initial release