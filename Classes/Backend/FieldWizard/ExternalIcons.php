<?php

declare(strict_types=1);

namespace StudioMitte\Riddle\Backend\FieldWizard;

/**
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Backend\Form\AbstractNode;

/**
 * Render external thumbnails
 */
class ExternalIcons extends AbstractNode
{
    /**
     * Render thumbnails of selected files
     *
     * @return array
     */
    public function render(): array
    {
        $selectIcons = [];
        $result = $this->initializeResultArray();

        $parameterArray = $this->data['parameterArray'];
        $selectItems = $parameterArray['fieldConf']['config']['items'];
        $selectItemCounter = 0;
        foreach ($selectItems as $item) {
            if ($item[1] === '--div--') {
                continue;
            }
            $icon = $item[2] ?? '';
            if ($icon) {
                $fieldValue = $this->data['databaseRow'][$this->data['fieldName']];
                $selectIcons[] = [
                    'title' => $item[0],
                    'active' => ($fieldValue[0] === (string)$item[1]) ? true : false,
                    'icon' => $icon,
                    'index' => $selectItemCounter,
                ];
            }
            $selectItemCounter++;
        }

        $html = [];
        if (!empty($selectIcons)) {
            $html[] = '<div class="t3js-forms-select-single-icons icon-list">';
            $html[] = '<div class="row">';
            foreach ($selectIcons as $i => $selectIcon) {
                $active = $selectIcon['active'] ? ' active' : '';
                $html[] = '<div class="item' . $active . '">';
                if (is_array($selectIcon)) {
                    $html[] = '<a href="#" title="' . htmlspecialchars($selectIcon['title'], ENT_COMPAT, 'UTF-8', false) . '" data-select-index="' . htmlspecialchars((string)$selectIcon['index']) . '">';
                    $html[] = sprintf('<span class="t3js-icon"><img src="%s" /></span>', $selectIcon['icon']);
                    $html[] = '</a>';
                }
                $html[] = '</div>';
            }
            $html[] = '</div>';
            $html[] = '</div>';
        }

        $result['html'] = implode(LF, $html);
        return $result;
    }
}
