<?php

declare(strict_types=1);

namespace StudioMitte\Riddle\Backend\Element;

/**
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Backend\Form\Element\SelectSingleElement;

/**
 * Hide the icon which is prefixed to the select by adding a hidden CSS class
 */
class SelectSingleElementWithNoIcon extends SelectSingleElement
{

    /**
     * @inheritDoc
     */
    public function render()
    {
        $resultArray = parent::render();
        $resultArray['html'] = str_replace('input-group-addon input-group-icon', 'input-group-addon input-group-icon hidden', $resultArray['html']);
        return $resultArray;
    }
}
