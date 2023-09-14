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
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\StringUtility;

class RiddleApi
{
    private const ENDPOINT = 'https://www.riddle.com/api/v3/';

    /** @var ExtensionConfiguration */
    protected $extensionConfiguration;

    public function __construct(ExtensionConfiguration $extensionConfiguration = null)
    {
        $this->extensionConfiguration = $extensionConfiguration ?: GeneralUtility::makeInstance(ExtensionConfiguration::class);
    }

    public function getRiddleList(): array
    {
        return $this->request('riddle/list');
    }

    public function getRiddleItem(int $id): array
    {
        $all = $this->getRiddleList();
        foreach ($all['response'] as $item) {
            if ((int)$item['UUID'] === $id) {
                return $item;
            }
        }
        return [];
    }

    public function getEmbedCode(int $id): ?array
    {
        return $this->request('riddle/embed-code/' . $id);
    }

    /**
     * @return array|string
     */
    protected function request(string $action, $method = 'POST')
    {
        $client = $this->getClient();
        $headers = [
            'Accept' => 'application/json',
        ];

        $uri = self::ENDPOINT . trim($action, '/');
        $headers['X-RIDDLE-BEARER'] = 'Bearer ' . $this->extensionConfiguration->getApiKey();

        $options['headers'] = $headers;
        $response = $client->request($method, $uri, $options);

        if ($response->getStatusCode() === 200) {
            if (\str_starts_with($action, 'riddle/embed-code')) {
                return $response->getBody()->getContents();
            } else {
                return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR)['data'];
            }
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

