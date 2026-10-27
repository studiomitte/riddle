<?php

declare(strict_types=1);

/*
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace StudioMitte\Riddle\Api;

use StudioMitte\Riddle\Exception\ApiConfigurationMissingException;
use StudioMitte\Riddle\ExtensionConfiguration;
use TYPO3\CMS\Core\Http\RequestFactory;

class RiddleApi
{
    private const ENDPOINT = 'https://www.riddle.com/api/v3/';

    public function __construct(
        protected readonly ExtensionConfiguration $extensionConfiguration,
        protected readonly RequestFactory $requestFactory,
    ) {}

    public function getRiddleList(): array
    {
        return $this->request('riddle/list');
    }

    public function getRiddleItem(string $id): array
    {
        foreach ($this->getRiddleList() as $item) {
            if (($item['UUID'] ?? '') === $id) {
                return $item;
            }
        }
        return [];
    }

    public function getEmbedCode(string $id): string
    {
        return $this->request('riddle/embed-code/' . $id, 'GET');
    }

    protected function request(string $action, string $method = 'POST'): array|string
    {
        $apiKey = $this->extensionConfiguration->getApiKey();
        if ($apiKey === '') {
            throw new ApiConfigurationMissingException('No riddle.com API key configured', 1598035744);
        }

        $response = $this->requestFactory->request(self::ENDPOINT . trim($action, '/'), $method, [
            'headers' => [
                'Accept' => 'application/json',
                'X-RIDDLE-BEARER' => 'Bearer ' . $apiKey,
            ],
        ]);

        if ($response->getStatusCode() === 200) {
            $content = $response->getBody()->getContents();
            if (str_starts_with($action, 'riddle/embed-code')) {
                return $content;
            }
            return json_decode($content, true, 512, JSON_THROW_ON_ERROR)['data'] ?? [];
        }

        throw new \UnexpectedValueException('Error for riddle request: ' . $response->getStatusCode(), 1597956586);
    }
}
