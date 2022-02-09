<?php

use yii\i18n\PhpMessageSource;

return [
    'translations' => [
        '*' => [
            'class' => PhpMessageSource::class,
            'fileMap' => [
                'project' => 'project.php',
                'user' => 'user.php',
            ],
        ],
    ],
];