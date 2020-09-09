<?php
declare(strict_types=1);

namespace StudioMitte\Riddle\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use StudioMitte\Riddle\Domain\LogItem;
use StudioMitte\Riddle\Domain\Repository\LogRepository;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\StringUtility;

class WebhookEndpoint implements MiddlewareInterface
{

    private const ENDPOINT = '/riddle-endpoint';

    /** @var LogRepository */
    protected $logRepository;

    public function __construct()
    {
        $this->logRepository = GeneralUtility::makeInstance(LogRepository::class);
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface
    {
        if (!StringUtility::beginsWith($request->getUri()->getPath(), self::ENDPOINT)) {
            return $handler->handle($request);
        }
        $json = $request->getParsedBody()['data'] ?? '';
        if ($json) {
            $data = json_decode($json, true);
            if ($data['riddleId'] ?? false) {
                $logItem = LogItem::convert($data);
                $this->logRepository->add($logItem);
            }
        }
        return new JsonResponse(['status' => 'ok']);
    }

}
