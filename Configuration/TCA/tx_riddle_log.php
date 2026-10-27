<?php

$ll = 'LLL:EXT:riddle/Resources/Private/Language/locallang.xlf:';

return [
    'ctrl' => [
        'title' => $ll . 'tx_riddle_log',
        'label' => 'riddle_title',
        'label_alt' => 'lead_email',
        'crdate' => 'crdate',
        'default_sortby' => 'crdate DESC',
        'typeicon_classes' => [
            'default' => 'ext-riddle-plugin',
        ],
        'readOnly' => true,
        'hideTable' => true,
    ],
    'types' => [
        '0' => [
            'showitem' => 'riddle_title, riddle_type, riddle_id, lead_name, lead_email, result_score_percentage',
        ],
    ],
    'columns' => [
        'riddle_title' => [
            'label' => $ll . 'tx_riddle_log.riddle_title',
            'config' => ['type' => 'input', 'readOnly' => true],
        ],
        'riddle_type' => [
            'label' => $ll . 'riddle.type',
            'config' => ['type' => 'input', 'readOnly' => true],
        ],
        'riddle_id' => [
            'label' => $ll . 'riddle.id',
            'config' => ['type' => 'number', 'readOnly' => true],
        ],
        'lead_name' => [
            'label' => $ll . 'tx_riddle_log.lead_name',
            'config' => ['type' => 'input', 'readOnly' => true],
        ],
        'lead_email' => [
            'label' => $ll . 'tx_riddle_log.lead_email',
            'config' => ['type' => 'input', 'readOnly' => true],
        ],
        'result_score_percentage' => [
            'label' => $ll . 'tx_riddle_log.result',
            'config' => ['type' => 'number', 'readOnly' => true],
        ],
    ],
];
