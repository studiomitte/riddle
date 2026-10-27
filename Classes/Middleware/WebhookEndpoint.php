<?php

declare(strict_types=1);

/*
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace StudioMitte\Riddle\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use StudioMitte\Riddle\Domain\LogItem;
use StudioMitte\Riddle\Domain\Repository\LogRepository;
use TYPO3\CMS\Core\Http\JsonResponse;

final readonly class WebhookEndpoint implements MiddlewareInterface
{
    private const ENDPOINT = '/riddle-endpoint';

    public function __construct(
        private LogRepository $logRepository,
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!str_starts_with($request->getUri()->getPath(), self::ENDPOINT)) {
            return $handler->handle($request);
        }
        $parsedBody = $request->getParsedBody();
        $json = is_array($parsedBody) ? (string)($parsedBody['data'] ?? '') : '';
        if ($json !== '') {
            $data = json_decode($json, true);
            if (is_array($data) && ($data['riddleId'] ?? false)) {
                $this->logRepository->add(LogItem::convert($data));
            }
        }
        return new JsonResponse(['status' => 'ok']);
    }
}
