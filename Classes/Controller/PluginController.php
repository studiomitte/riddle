<?php

declare(strict_types=1);

/*
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace StudioMitte\Riddle\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use StudioMitte\Riddle\Api\RiddleApi;
use StudioMitte\Riddle\Utility\RiddleUtility;
use TYPO3\CMS\Core\Attribute\AsAllowedCallable;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * Renders the riddle embed code, called as USER userFunc
 */
final readonly class PluginController
{
    public function __construct(
        private RiddleApi $riddleApi,
        private LoggerInterface $logger,
    ) {}

    #[AsAllowedCallable]
    public function run(string $content, array $conf, ServerRequestInterface $request): string
    {
        $contentObject = $request->getAttribute('currentContentObject');
        if (!$contentObject instanceof ContentObjectRenderer) {
            return '';
        }
        $riddleId = RiddleUtility::getRiddleId((string)($contentObject->data['pi_flexform'] ?? ''));
        if ($riddleId === '') {
            return '';
        }

        try {
            return $this->riddleApi->getEmbedCode($riddleId);
        } catch (\Exception $e) {
            $this->logger->error('Riddle "{id}" could not be embedded: {message}', ['id' => $riddleId, 'message' => $e->getMessage()]);
            return '';
        }
    }
}
