<?php

declare(strict_types=1);

/*
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace StudioMitte\Riddle;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration as CoreExtensionConfiguration;

final readonly class ExtensionConfiguration
{
    public function __construct(
        private CoreExtensionConfiguration $extensionConfiguration,
    ) {}

    public function getApiKey(): string
    {
        try {
            return (string)($this->extensionConfiguration->get('riddle', 'apiKey') ?? '');
        } catch (\Exception) {
            return '';
        }
    }
}
