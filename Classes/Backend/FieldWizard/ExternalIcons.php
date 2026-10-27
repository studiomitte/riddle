<?php

declare(strict_types=1);

/*
 * This file is part of the "riddle" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace StudioMitte\Riddle\Backend\FieldWizard;

use TYPO3\CMS\Backend\Form\AbstractNode;

/**
 * Render external thumbnails
 */
class ExternalIcons extends AbstractNode
{
    public function render(): array
    {
        $selectIcons = [];
        $result = $this->initializeResultArray();

        $parameterArray = $this->data['parameterArray'];
        $selectItems = $parameterArray['fieldConf']['config']['items'] ?? [];
        $fieldValue = $parameterArray['itemFormElValue'] ?? '';
        $fieldValue = (string)(is_array($fieldValue) ? ($fieldValue[0] ?? '') : $fieldValue);

        foreach ($selectItems as $index => $item) {
            $icon = (string)($item['icon'] ?? '');
            if ((string)($item['value'] ?? '') === '' || $icon === '') {
                continue;
            }
            $selectIcons[] = [
                'title' => (string)$item['label'],
                'active' => $fieldValue === (string)$item['value'],
                'icon' => $icon,
                'index' => $index,
            ];
        }

        $html = [];
        if (!empty($selectIcons)) {
            $html[] = '<div class="t3js-forms-select-single-icons form-wizard-icon-list">';
            foreach ($selectIcons as $selectIcon) {
                $html[] = '<div class="form-wizard-icon-list-item">';
                $html[] = '<button type="button" class="' . ($selectIcon['active'] ? 'active' : '') . '"'
                    . ' title="' . htmlspecialchars($selectIcon['title'], ENT_COMPAT, 'UTF-8', false) . '"'
                    . ' data-select-index="' . htmlspecialchars((string)$selectIcon['index']) . '">';
                $html[] = '<img src="' . htmlspecialchars($selectIcon['icon']) . '" alt="" loading="lazy" style="max-width: 128px;" />';
                $html[] = '</button>';
                $html[] = '</div>';
            }
            $html[] = '</div>';
        }

        $result['html'] = implode(LF, $html);
        return $result;
    }
}
