# Readers service for the SumUp Ecommerce SDK for PHP

## \SumUp\Services\Readers

The `\SumUp\Services\Readers` service is responsible for getting information about Readers.

```php
$Readerservice = new \SumUp\Services\Readers(
    \SumUp\HttpClients\SumUpHttpClientInterface $client,
    \SumUp\Authentication\AccessToken $accessToken
);
```

## Instance Methods

### getReaders()

Returns information about Readers.

```php
public function getReaders(
    string $merchantCode,
): \SumUp\HttpClients\Response
```

Returns a `\SumUp\HttpClients\Response` or throws an exception.

### createReader()

Create a new reader linked to the merchant account.

```php
public function createReader(
    string $merchantCode,
    string $pairingCode,
    string $name = null,
    array  $meta = null
): \SumUp\HttpClients\Response
```

Returns a `\SumUp\HttpClients\Response` or throws an exception.

### getReader()

Gets a Reader.

```php
public function getReader(
    string $merchantCode,
    string $idReader
): \SumUp\HttpClients\Response
```

Returns a `\SumUp\HttpClients\Response` or throws an exception.

### deleteReader()

Deletes a Reader.

```php
public function deleteReader(
    string $merchantCode,
    string $idReader
): \SumUp\HttpClients\Response
```

Returns a `\SumUp\HttpClients\Response` or throws an exception.

### updateReader()

Updates a Reader.

```php
public function updateReader(
    string $merchantCode,
    string $idReader,
    string $name = null,
    array  $meta = null
): \SumUp\HttpClients\Response
```

Returns a `\SumUp\HttpClients\Response` or throws an exception.

### createCheckout()

Create a Checkout for a Reader.

This process is asynchronous and the actual transaction may take some time to be stared on the device.

There are some caveats when using this endpoint:

The target device must be online, otherwise checkout won't be accepted
After the checkout is accepted, the system has 60 seconds to start the payment on the target device. During this time, any other checkout for the same device will be rejected.
Note: If the target device is a Solo, it must be in version 3.3.24.3 or higher.

```php
$totalAmount = [
    'currency' => 'EUR' //Currency ISO 4217 code
    'minor_unit' => 2 //The minor units of the currency. It represents the number of decimals of the currency. For the currencies CLP, COP and HUF, the minor unit is 0.
    'value' => 100 //Total amount of the transaction. It must be a positive integer.
];
```
Amount of the transaction. The amount is represented as an integer value altogether with the currency and the minor unit. For example, EUR 1.00 is represented as value 100 with minor unit of 2.

```php
public function createCheckout(
    string $merchantCode,
    string $idReader,
    array  $totalAmount, 
    string $description = null,
    string $returnUrl = null,
    int    $tipRates = null,
    string $cardType = null,
    array  $affiliateMetadata = null
): \SumUp\HttpClients\Response
```

Returns a `\SumUp\HttpClients\Response` or throws an exception.