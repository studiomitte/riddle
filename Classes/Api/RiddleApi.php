<?php

declare(strict_types=1);

namespace StudioMitte\Riddle\Api;

/**
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use StudioMitte\Riddle\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\StringUtility;

class RiddleApi
{
    private const ENDPOINT = 'https://www.riddle.com/api/v2/';
    private const ENDPOINTV2 = 'https://www.riddle.com/creator/v2/api/';

    /** @var ExtensionConfiguration */
    protected $extensionConfiguration;

    public function __construct(ExtensionConfiguration $extensionConfiguration = null)
    {
        $this->extensionConfiguration = $extensionConfiguration ?: GeneralUtility::makeInstance(ExtensionConfiguration::class);
    }

    public function getRiddleList(): array
    {
        if (!$this->extensionConfiguration->isV1Enabled()) {
            return [];
        }
        return $this->request('riddle/get/list');
    }

    public function getRiddleListV2(): array
    {
        if (!$this->extensionConfiguration->isV2Enabled()) {
            return [];
        }
        return $this->request('riddle/list', 'POST', true);
    }

    public function getRiddleItem(int $id): array
    {
        $all = $this->getRiddleList();
        foreach ($all['response']['items'] as $item) {
            if ((int)$item['id'] === $id) {
                return $item;
            }
        }
        return [];
    }

    public function getRiddleItemV2(string $id): array
    {
        $data = $this->request('riddle/' . $id, 'GET', true);
        return $data['data'] ?? [];
    }

    public function getEmbedCode(int $id): ?array
    {
        return $this->request('riddle/get/embed-code?riddleId=' . $id);
    }

    public function getEmbedCodeV2(string $id): string
    {
        return (string)$this->request('riddle/embed-code/' . $id, 'GET', true);
    }

    /**
     * @return array|string
     */
    protected function request(string $action, $method = 'GET', $isV2 = false)
    {
        $client = $this->getClient();
        $headers = [
            'Accept' => 'application/json',
        ];
        if ($isV2) {
            $uri = self::ENDPOINTV2 . trim($action, '/');
            $headers['X-RIDDLE-BEARER'] = 'Bearer ' . $this->extensionConfiguration->getApiTokenV2();
        } else {
            $uri = self::ENDPOINT . trim($action, '/');
            $headers['Authorization'] = 'Bearer ' . $this->extensionConfiguration->getApiToken();
            $headers['Key'] = $this->extensionConfiguration->getApiKey();
        }

        $options['headers'] = $headers;
        $response = $client->request($method, $uri, $options);

        if ($response->getStatusCode() === 200) {
            if ($isV2 && StringUtility::beginsWith($action, 'riddle/embed-code')) {
                return $response->getBody()->getContents();
            } else {
                return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
            }
            return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        }

        throw new \UnexpectedValueException('Error for riddle request: ' . $response->getStatusCode(), 1597956586);
    }


    /**
     * Creates the client to do requests
     * @return ClientInterface
     */
    private function getClient(): ClientInterface
    {
        $httpOptions = $GLOBALS['TYPO3_CONF_VARS']['HTTP'];
        $httpOptions['verify'] = filter_var($httpOptions['verify'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? $httpOptions['verify'];
        if (isset($GLOBALS['TYPO3_CONF_VARS']['HTTP']['handler']) && is_array($GLOBALS['TYPO3_CONF_VARS']['HTTP']['handler'])) {
            $stack = HandlerStack::create();
            foreach ($GLOBALS['TYPO3_CONF_VARS']['HTTP']['handler'] ?? [] as $handler) {
                $stack->push($handler);
            }
            $httpOptions['handler'] = $stack;
        }
        return GeneralUtility::makeInstance(Client::class, $httpOptions);
    }
}

