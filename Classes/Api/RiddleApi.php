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
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use StudioMitte\Riddle\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class RiddleApi
{
    private const ENDPOINT = 'https://www.riddle.com/api/v2/';

    /** @var ExtensionConfiguration */
    protected $extensionConfiguration;

    public function __construct(ExtensionConfiguration $extensionConfiguration = null)
    {
        $this->extensionConfiguration = $extensionConfiguration ?: GeneralUtility::makeInstance(ExtensionConfiguration::class);
    }

    public function getRiddleList(): array
    {
        return $this->request('riddle/get/list');
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

    public function getEmbedCode(int $id): ?array
    {
        return $this->request('riddle/get/embed-code?riddleId=' . $id);
    }

    /**
     * @param string $action
     * @param array $options
     * @param string $method
     * @return array
     * @throws GuzzleException
     */
    protected function request(string $action, array $options = [], $method = 'GET'): array
    {
        $uri = self::ENDPOINT . trim($action, '/');
        $client = $this->getClient();
        $headers = [
            'Authorization' => 'Bearer ' . $this->extensionConfiguration->getApiToken(),
            'Key' => $this->extensionConfiguration->getApiKey(),
            'Accept' => 'application/json',
        ];
        $options['headers'] = $headers;
        $response = $client->request($method, $uri, $options);

        if ($response->getStatusCode() === 200) {
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
