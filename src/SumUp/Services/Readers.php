<?php

namespace SumUp\Services;

use SumUp\HttpClients\SumUpHttpClientInterface;
use SumUp\Authentication\AccessToken;
use SumUp\Exceptions\SumUpArgumentException;
use SumUp\Utils\ExceptionMessages;
use SumUp\Utils\Headers;

/**
 * Class Readers
 *
 * @package SumUp\Services
 */
class Readers implements SumUpService
{
    /**
     * The client for the http communication.
     *
     * @var SumUpHttpClientInterface
     */
    protected $client;

    /**
     * The access token needed for authentication for the services.
     *
     * @var AccessToken
     */
    protected $accessToken;

    /**
     * Customers constructor.
     *
     * @param SumUpHttpClientInterface $client
     * @param AccessToken $accessToken
     */
    public function __construct(SumUpHttpClientInterface $client, AccessToken $accessToken)
    {
        $this->client = $client;
        $this->accessToken = $accessToken;
    }

    /**
     * Get a list of all readers of the merchant.
     *
     * @param string $merchantCode
     *
     * @return \SumUp\HttpClients\Response
     *
     * @throws SumUpArgumentException
     * @throws \SumUp\Exceptions\SumUpConnectionException
     * @throws \SumUp\Exceptions\SumUpResponseException
     * @throws \SumUp\Exceptions\SumUpAuthenticationException
     * @throws \SumUp\Exceptions\SumUpSDKException
     */
    public function getReaders($merchantCode)
    {
        if (empty($merchantCode)) {
            throw new SumUpArgumentException(ExceptionMessages::getMissingParamMsg('merchant code'));
        }

        $path = "/v0.1/merchants/{$merchantCode}/readers";
        $headers = array_merge(Headers::getStandardHeaders(), Headers::getAuth($this->accessToken));
        return $this->client->send('GET', $path, null, $headers);
    }

    /**
     * Create a new reader linked to the merchant account.
     *
     * @param string $merchantCode
     * @param string $pairingCode
     * @param int    $name
     * @param array  $meta
     *
     * @return \SumUp\HttpClients\Response
     *
     * @throws SumUpArgumentException
     * @throws \SumUp\Exceptions\SumUpConnectionException
     * @throws \SumUp\Exceptions\SumUpResponseException
     * @throws \SumUp\Exceptions\SumUpAuthenticationException
     * @throws \SumUp\Exceptions\SumUpSDKException
     */
    public function createReader($merchantCode, $pairingCode, $name = null, $meta = null)
    {
        if (empty($merchantCode)) {
            throw new SumUpArgumentException(ExceptionMessages::getMissingParamMsg('merchant code'));
        }
        if (empty($pairingCode)) {
            throw new SumUpArgumentException(ExceptionMessages::getMissingParamMsg('pairing code'));
        }

        $payload = [
            'pairing_code' => $pairingCode
        ];

        if ($name !== null) {
            $payload['name'] = $name;
        }
        if ($meta !== null) {
            $payload['meta'] = $meta;
        }
        $path = "/v0.1/merchants/{$merchantCode}/readers";
        $headers = array_merge(Headers::getStandardHeaders(), Headers::getAuth($this->accessToken));
        return $this->client->send('POST', $path, $payload, $headers);
    }

    /**
     * Gets a Reader.
     *
     * @param string $merchantCode
     * @param string $idReader
     *
     * @return \SumUp\HttpClients\Response
     *
     * @throws SumUpArgumentException
     * @throws \SumUp\Exceptions\SumUpConnectionException
     * @throws \SumUp\Exceptions\SumUpResponseException
     * @throws \SumUp\Exceptions\SumUpAuthenticationException
     * @throws \SumUp\Exceptions\SumUpSDKException
     */
    public function getReader($merchantCode, $idReader) {
        if (empty($merchantCode)) {
            throw new SumUpArgumentException(ExceptionMessages::getMissingParamMsg('merchant code'));
        }

        if (empty($idReader)) {
            throw new SumUpArgumentException(ExceptionMessages::getMissingParamMsg('id reader'));
        }

        $path = "/v0.1/merchants/{$merchantCode}/readers/{$idReader}";
        $headers = array_merge(Headers::getStandardHeaders(), Headers::getAuth($this->accessToken));
        return $this->client->send('GET', $path, null, $headers);
    }

    /**
     * Deletes a Reader.
     *
     * @param string $merchantCode
     * @param string $idReader
     *
     * @return \SumUp\HttpClients\Response
     *
     * @throws SumUpArgumentException
     * @throws \SumUp\Exceptions\SumUpConnectionException
     * @throws \SumUp\Exceptions\SumUpResponseException
     * @throws \SumUp\Exceptions\SumUpAuthenticationException
     * @throws \SumUp\Exceptions\SumUpSDKException
     */
    public function deleteReader($merchantCode, $idReader) {
        if (empty($merchantCode)) {
            throw new SumUpArgumentException(ExceptionMessages::getMissingParamMsg('merchant code'));
        }

        if (empty($idReader)) {
            throw new SumUpArgumentException(ExceptionMessages::getMissingParamMsg('id reader'));
        }

        $path = "/v0.1/merchants/{$merchantCode}/readers/{$idReader}";
        $headers = array_merge(Headers::getStandardHeaders(), Headers::getAuth($this->accessToken));
        return $this->client->send('DELETE', $path, null, $headers);
    }

    /**
     * Updates a Reader.
     *
     * @param string $merchantCode
     * @param string $idReader
     *
     * @return \SumUp\HttpClients\Response
     *
     * @throws SumUpArgumentException
     * @throws \SumUp\Exceptions\SumUpConnectionException
     * @throws \SumUp\Exceptions\SumUpResponseException
     * @throws \SumUp\Exceptions\SumUpAuthenticationException
     * @throws \SumUp\Exceptions\SumUpSDKException
     */
    public function updateReader($merchantCode, $idReader, $name = null, $meta = null) {
        if (empty($merchantCode)) {
            throw new SumUpArgumentException(ExceptionMessages::getMissingParamMsg('merchant code'));
        }
        if (empty($idReader)) {
            throw new SumUpArgumentException(ExceptionMessages::getMissingParamMsg('id reader'));
        }

        $payload = [];

        if ($name !== null) {
            $payload['name'] = $name;
        }
        if ($meta !== null) {
            $payload['meta'] = $meta;
        }

        $path = "/v0.1/merchants/{$merchantCode}/readers/{$idReader}";
        $headers = array_merge(Headers::getStandardHeaders(), Headers::getAuth($this->accessToken));
        return $this->client->send('PATCH', $path, $payload, $headers);
    }

    /**
     * Create a Checkout for a Reader.

     * This process is asynchronous and the actual transaction may take some time to be stared on the device.
     * There are some caveats when using this endpoint:
     * The target device must be online, otherwise checkout won't be accepted
     * After the checkout is accepted, the system has 60 seconds to start the payment on the target device. During this time, any other checkout for the same device will be rejected.
     * Note: If the target device is a Solo, it must be in version 3.3.24.3 or higher.
     *
     * @param string $merchantCode
     * @param string $idReader
     *
     * @return \SumUp\HttpClients\Response
     *
     * @throws SumUpArgumentException
     * @throws \SumUp\Exceptions\SumUpConnectionException
     * @throws \SumUp\Exceptions\SumUpResponseException
     * @throws \SumUp\Exceptions\SumUpAuthenticationException
     * @throws \SumUp\Exceptions\SumUpSDKException
     */

    public function createCheckout($merchantCode, $idReader, $totalAmount, $description = null, $returnUrl = null, $tipRates = null, $cardType = null, $affiliateMetadata = null) {
        if (empty($merchantCode)) {
            throw new SumUpArgumentException(ExceptionMessages::getMissingParamMsg('merchant code'));
        }
        if (empty($idReader)) {
            throw new SumUpArgumentException(ExceptionMessages::getMissingParamMsg('id reader'));
        }
        if (empty($totalAmount) && is_array($totalAmount)) {
            throw new SumUpArgumentException(ExceptionMessages::getMissingParamMsg('total amount'));
        }

        $payload = [];

        $payload = [
            'total_amount' => $totalAmount,
        ];

        if ($description !== null) {
            $payload['description'] = $description;
        }
        if ($returnUrl !== null) {
            $payload['return_url'] = $returnUrl;
        }
        if ($tipRates !== null) {
            $payload['tip_rates'] = $tipRates;
        }
        if ($cardType !== null) {
            $payload['card_type'] = $cardType;
        }
        if ($affiliateMetadata !== null) {
            $payload['affiliate'] = $affiliateMetadata;
        }

        $path = "/v0.1/merchants/{$merchantCode}/readers/{$idReader}/checkout";
        $headers = array_merge(Headers::getStandardHeaders(), Headers::getAuth($this->accessToken));
        return $this->client->send('POST', $path, $payload, $headers);
    }

    /**
     * Create a Terminate action for a Reader.

     * It stops the current transaction on the target device.
     * 
     * This process is asynchronous and the actual termination may take some time to be performed on the device.
     * 
     * There are some caveats when using this endpoint:
     * 
     * The target device must be online, otherwise terminate won't be accepted
     * The action will succeed only if the device is waiting for cardholder action: e.g: waiting for card, waiting for PIN, etc.
     * There is no confirmation of the termination.
     * If a transaction is successfully terminated and return_url was provided on Checkout, the transaction status will be sent as failed to the provided URL.
     * 
     * Note: If the target device is a Solo, it must be in version 3.3.28.0 or higher.
     *
     * @param string $merchantCode
     * @param string $idReader
     *
     * @return \SumUp\HttpClients\Response
     *
     * @throws SumUpArgumentException
     * @throws \SumUp\Exceptions\SumUpConnectionException
     * @throws \SumUp\Exceptions\SumUpResponseException
     * @throws \SumUp\Exceptions\SumUpAuthenticationException
     * @throws \SumUp\Exceptions\SumUpSDKException
     */

    public function readerTerminate($merchantCode, $idReader) {
        if (empty($merchantCode)) {
            throw new SumUpArgumentException(ExceptionMessages::getMissingParamMsg('merchant code'));
        }
        if (empty($idReader)) {
            throw new SumUpArgumentException(ExceptionMessages::getMissingParamMsg('id reader'));
        }

        $payload = [];

        $path = "/v0.1/merchants/{$merchantCode}/readers/{$idReader}/terminate";
        $headers = array_merge(Headers::getStandardHeaders(), Headers::getAuth($this->accessToken));
        return $this->client->send('POST', $path, $payload, $headers);
    }
}
