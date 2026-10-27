<?php

declare(strict_types=1);

/*
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace StudioMitte\Riddle\Backend\Element;

use TYPO3\CMS\Backend\Form\Element\SelectSingleElement;

/**
 * Hide the icon which is prefixed to the select, as riddle icons are full-size remote images
 */
class SelectSingleElementWithNoIcon extends SelectSingleElement
{
    public function render(): array
    {
        $resultArray = parent::render();
        $resultArray['html'] = str_replace('input-group-text input-group-icon"', 'input-group-text input-group-icon d-none"', $resultArray['html']);
        return $resultArray;
    }
}
