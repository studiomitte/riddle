<?php

declare(strict_types=1);

/*
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace StudioMitte\Riddle\Upgrades;

use TYPO3\CMS\Core\Attribute\UpgradeWizard;
use TYPO3\CMS\Core\Upgrades\AbstractListTypeToCTypeUpdate;

#[UpgradeWizard('riddlePluginListTypeToCTypeUpdate')]
final class PluginListTypeToCTypeUpdate extends AbstractListTypeToCTypeUpdate
{
    protected function getListTypeToCTypeMapping(): array
    {
        return [
            'riddle_riddle' => 'riddle_riddle',
        ];
    }

    public function getTitle(): string
    {
        return 'Migrate "riddle" plugins to content elements';
    }

    public function getDescription(): string
    {
        return 'The riddle plugin is now registered as its own content element type (CType). This wizard migrates existing plugin records and backend user group permissions.';
    }
}
