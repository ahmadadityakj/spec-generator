# Spec Generator
Product Specification Generator Based on Order Detail

## Instalation
composer require ahmadadityakj/spec-generator

## Usage
```php
$specObj = new SpecGenerator();
//type -> (v3, arterous, shop, moments, panorama, custom_orders)
$specArray = $specObj->generate($typeString, $orderDetailObject, $skuObject);

```