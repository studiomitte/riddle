<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:plain_faq/Resources/Private/Language/locallang_db.xlf:tx_plainfaq_domain_model_faq',
        'label' => 'uid',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'sortby' => 'sorting',
        'searchFields' => 'question,answer,keywords,images,files',
        'typeicon_classes' => [
            'default' => 'ext-plainfaq-faq'
        ],
        'readOnly' => true
    ],
    'types' => [
        '1' => ['showitem' => '
            question, slug, answer, keywords,'
        ],
    ],
    'palettes' => [
        'language' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource'],
    ],
    'columns' => [


    ],
];
