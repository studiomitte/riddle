<?php

declare(strict_types=1);

/*
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace StudioMitte\Riddle\Tests\Unit\Api;

use PHPUnit\Framework\Attributes\Test;
use StudioMitte\Riddle\Api\RiddleApi;
use StudioMitte\Riddle\Exception\ApiConfigurationMissingException;
use StudioMitte\Riddle\ExtensionConfiguration;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration as CoreExtensionConfiguration;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

final class RiddleApiTest extends UnitTestCase
{
    private function createSubject(string $apiKey, ?Response $response = null): RiddleApi
    {
        $coreExtensionConfiguration = $this->createMock(CoreExtensionConfiguration::class);
        $coreExtensionConfiguration->method('get')->with('riddle', 'apiKey')->willReturn($apiKey);

        $requestFactory = $this->createMock(RequestFactory::class);
        if ($response !== null) {
            $requestFactory->expects(self::once())->method('request')
                ->with(
                    self::stringStartsWith('https://www.riddle.com/api/v3/'),
                    self::anything(),
                    self::callback(static fn(array $options): bool => $options['headers']['X-RIDDLE-BEARER'] === 'Bearer ' . $apiKey)
                )
                ->willReturn($response);
        } else {
            $requestFactory->expects(self::never())->method('request');
        }

        return new RiddleApi(new ExtensionConfiguration($coreExtensionConfiguration), $requestFactory);
    }

    private function createResponse(string $body, int $status = 200): Response
    {
        $response = new Response('php://temp', $status);
        $response->getBody()->write($body);
        $response->getBody()->rewind();
        return $response;
    }

    #[Test]
    public function riddleListReturnsDataOfResponse(): void
    {
        $riddles = [['UUID' => 'abc', 'title' => 'riddle 1']];
        $subject = $this->createSubject('key', $this->createResponse(json_encode(['data' => $riddles])));

        self::assertSame($riddles, $subject->getRiddleList());
    }

    #[Test]
    public function riddleItemIsFoundByUuid(): void
    {
        $riddles = [['UUID' => 'abc', 'title' => 'riddle 1'], ['UUID' => 'def', 'title' => 'riddle 2']];
        $subject = $this->createSubject('key', $this->createResponse(json_encode(['data' => $riddles])));

        self::assertSame($riddles[1], $subject->getRiddleItem('def'));
    }

    #[Test]
    public function unknownRiddleItemReturnsEmptyArray(): void
    {
        $subject = $this->createSubject('key', $this->createResponse(json_encode(['data' => [['UUID' => 'abc']]])));

        self::assertSame([], $subject->getRiddleItem('xyz'));
    }

    #[Test]
    public function embedCodeIsReturnedAsString(): void
    {
        $subject = $this->createSubject('key', $this->createResponse('<div>embed</div>'));

        self::assertSame('<div>embed</div>', $subject->getEmbedCode('abc'));
    }

    #[Test]
    public function nonSuccessfulResponseThrowsException(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionCode(1597956586);

        $this->createSubject('key', $this->createResponse('', 204))->getRiddleList();
    }

    #[Test]
    public function missingApiKeyThrowsException(): void
    {
        $this->expectException(ApiConfigurationMissingException::class);
        $this->expectExceptionCode(1598035744);

        $this->createSubject('')->getRiddleList();
    }
}
